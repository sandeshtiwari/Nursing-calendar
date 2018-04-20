<?php
	class Admin
	{
		public function __construct($con, $email)
		{
			$this->con = $con;
			$this->email = $_SESSION['email'];
		}
		public function getJSON()
		{
			$data = array();
			$databaseQuery = "SELECT DISTINCT occupied_ID, occupied.Course_ID as id, Number, Title as title
			FROM course, occupied WHERE occupied.Course_ID = course.Course_ID";
			$result = mysqli_query($this->con, $databaseQuery);
			while($row = mysqli_fetch_assoc($result))
			{
				$Course_ID = $row['id'];
				$occupied_ID = $row['occupied_ID'];
				// query to get the days and class time for a class
				$dates = mysqli_query($this->con, "SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM occupied, week
				 WHERE occupied.Week_ID = week.ID AND occupied.Course_ID = '$Course_ID' AND occupied_ID = '$occupied_ID'");
				$dateRow = mysqli_fetch_assoc($dates);
				// query to get the days and class time for a class
				$courseDay = mysqli_query($this->con, "SELECT O.M, O.T, O.W, O.R, O.F, Start_time, End_time FROM course, occupied as O 
					WHERE O.Course_ID = course.Course_ID AND O.Course_ID = '$Course_ID' AND O.occupied_ID = '$occupied_ID'");
				$days = mysqli_fetch_assoc($courseDay);
				$dow = $this->getDaysOfWeek($days);
				$row['title'] .= $this->getRoomNames($Course_ID,$occupied_ID);
				$row['title'] = $Course_ID."\n".$row['title'];
				$row['dow'] = $dow;
				$row['dowstart'] = date('Y-m-d', strtotime($dateRow['start_date']));
				$row['dowend'] = date('Y-m-d', strtotime($dateRow['end_date']));
				$row['start'] = $days['Start_time'];
				$row['end'] = $days['End_time'];
				$data[] = $row;
			}
			return json_encode($data);
		}
		public function giveLogs()
		{
			$logs = array();
			//"SELECT CWID, Fname, Lname, email FROM person WHERE role = 'student'"
			$string = "SELECT * FROM logs ORDER BY Time DESC";
			$query = mysqli_query($this->con, $string);
			while($log = mysqli_fetch_assoc($query))
			{
				$oneLog = array();
				$oneLog['CWID'] = $log['CWID'];
				$oneLog['Name'] = $log['Name'];
				$oneLog['activity'] = $log['activity'];
				$oneLog['Course_ID'] = $log['Course_ID'];
				$oneLog['Room_ID'] = $log['Room_ID'];
				$oneLog['Week_ID'] = $log['Week_ID'];
				$oneLog['Time'] = $log['Time'];
				$logs[] = $oneLog; 
			}
			return $logs;
		}
		public function deleteLogs()
		{
			$string = "DELETE FROM logs";
			$query = mysqli_query($this->con, $string);
			return $query;
		}
		private function updateLog($activity, $course_id, $room_id, $week)
		{
			$email = $_SESSION['email'];
			$string = "SELECT Fname, Lname, CWID FROM person WHERE email = '$email' LIMIT 1";

			$query = mysqli_query($this->con, $string);
			$result = mysqli_fetch_assoc($query);
			$fname = $result['Fname'];
			$lname = $result['Lname'];
			$cwid = $result['CWID'];
			$name = $fname." ".$lname;
			$string = "INSERT INTO logs(CWID, Name, activity, Course_ID, Room_ID, Week_ID, Time) VALUES($cwid, '$name', '$activity', $course_id, $room_id, $week, now())";
			$query = mysqli_query($this->con, $string);
			return $string;
		}
		// method to add collision
		public function addCollision($room_id, $course_id, $semester_id, $week, $day, $roomToDelete)
		{
			$collision_day = substr($day, 0, 1);
			$days['M'] = 'no';
			$days['T'] = 'no';
			$days['W'] = 'no';
			$days['R'] = 'no';
			$days['F'] = 'no';
			$days[$collision_day] = 'yes';
			$collision_id = $this->getUniqueCollID();
			$string = "INSERT INTO collision(Course_ID, Coll_ID, Room_ID, M, T, W, R, F, Week_ID, Semester_ID) 
			VALUES($course_id, $collision_id, $room_id, '".$days['M']."', '".$days['T']."','".$days['W']."','".$days['R']."','".$days['F']."', $week, $semester_id)";
			$query = mysqli_query($this->con, $string);
			$this->updateLog("Request - ".$day, $course_id, $room_id, $week);
			 //return $this->updateOccupied($course_id, $roomToDelete, $week, $day, $semester_id);
		}
		// method to update the collision table after the collision is resolved
        public function updateOccupied($course_id, $room_id, $week_id, $day, $semester_id)
		{
			$fullDay = $day;
			$day = substr($day, 0, 1);
			$string = "UPDATE occupied SET $day = 'no'
			WHERE Course_ID = '$course_id' AND Room_ID='$room_id' AND Week_ID = $week_id AND Semester_ID = $semester_id";
			$query = mysqli_query($this->con, $string);
			$this->sanitize("occupied");
			$this->updateLog("Deleted - ".$fullDay, $course_id, $room_id, $week_id);
			return $string;
		}
		// function to get a unique collision id which is not already in the table
		private function getUniqueCollID()
		{
			$string = "SELECT FLOOR(RAND() * 99999) AS Coll_ID 
			FROM collision 
			WHERE 'Coll_ID' NOT IN (SELECT Coll_ID FROM collision) LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			$string = "SELECT Coll_ID FROM collision";
			$query = mysqli_query($this->con, $string);
			$collision_id = mysqli_fetch_assoc($query);
			if(empty($collision_id))
			{
				return 1;
			}
			return $row['Coll_ID'];
		}
        // method to delete request
        public function deleteRequest($course_id, $room_id, $week_id, $day, $semester_id)
		{
			$fullDay = $day;
			$day = substr($day, 0, 1);
			$string = "UPDATE collision SET $day = 'no'
			WHERE Course_ID = $course_id AND Room_ID = $room_id AND Week_ID = $week_id AND Semester_ID = $semester_id";
			$query = mysqli_query($this->con, $string);
			$this->sanitize("collision");
			$this->updateLog("Deleted - ".$fullDay, $course_id, $room_id, $week_id);
		}
        // method to give access to a requesting course_id
        public function giveAccess($collidingCoures,$course_id,$room_id,$week_id,$day,$semester_id)
		{
			$fullDay = $day;
			$day = substr($day, 0, 1);
			$days = $this->giveDays($day);
			$occupied_id = $this->getUniqueOccupiedID();
			$string = "INSERT INTO occupied(Course_ID, Room_ID, Semester_ID,M, T, W, R, F, Week_ID, occupied_ID)
			 VALUES('$course_id', '$room_id', '$semester_id', '".$days['M']."', '".$days['T']."', '".$days['W']."', '".$days['R']."', '".$days['F']."',$week_id, $occupied_id)";
			$query = mysqli_query($this->con, $string);
			$this->updateLog("Added - ".$fullDay, $course_id, $room_id, $week_id);
			$string = "UPDATE occupied SET $day ='no' 
			WHERE Room_ID = $room_id AND Week_ID = $week_id AND Course_ID = $collidingCoures AND Semester_ID = $semester_id";
			$query = mysqli_query($this->con, $string);
			$this->updateLog("Deleted course- ".$fullDay, $collidingCoures, $room_id, $week_id);
			$this->sanitize("occupied");
			$this->updateCollision($course_id, $room_id, $week_id, $day, $semester_id,$fullDay);
		}
        // method to update the collision table after the collision is resolved
        private function updateCollision($course_id, $room_id, $week_id, $day, $semester_id, $fullDay)
		{
			$string = "UPDATE collision SET $day = 'no'
			WHERE Course_ID = '$course_id' AND Room_ID='$room_id' AND Week_ID = $week_id AND Semester_ID = $semester_id";
			$query = mysqli_query($this->con, $string);
			$this->updateLog("Deleted collision- ".$fullDay, $course_id, $room_id, $week_id);
			$this->sanitize("collision");
		}
        // method to delete any unwanted rows from a given table
        private function sanitize($tableName)
		{
			$string = "DELETE FROM $tableName WHERE M = 'no' AND T = 'no' AND W = 'no' AND R = 'no' AND F = 'no'";
			$query = mysqli_query($this->con, $string);
		}
        // method to get a unique occupied id which is not already in the table
		private function getUniqueOccupiedID()
		{
			$string = "SELECT MAX(occupied_ID) as occupied_id FROM occupied";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			if(empty($row))
			{
				return 1;
			}
			return $row['occupied_id']+1;
		}
        // method to give days
        private function giveDays($day)
		{
			$days['M'] = 'no';
			$days['T'] = 'no';
			$days['W'] = 'no';
			$days['R'] = 'no';
			$days['F'] = 'no';
			$days[$day] = 'yes';
			return $days;
		}
        // method to give the colliding course name
		public function giveCollidingCourse($room_id, $week_id, $day, $semester_id)
		{
			$day = substr($day, 0, 1);
			$string = "SELECT Course_ID FROM occupied
			 WHERE Room_ID = $room_id AND Week_ID = $week_id AND Semester_ID = $semester_id AND $day = 'yes'";
			 $query = mysqli_query($this->con, $string);
			 $course = mysqli_fetch_assoc($query);
			 return $course['Course_ID'];
		}
        // method to give the name of the course from a given course id
        public function giveCourseName($course_id)
		{
			$string = "SELECT Prefix, Number, Title FROM course WHERE Course_ID = $course_id";
			$query = mysqli_query($this->con, $string);
			$course = mysqli_fetch_assoc($query);
			$courseDetails = $course['Prefix']." ".$course['Number']." ".$course['Title'];
			return $courseDetails;
		}
		private function getRoomNames($Course_ID, $occupied_ID)
		{
			$roomString = "SELECT room.Name as roomName FROM room, occupied WHERE room.ID = occupied.Room_ID AND Course_ID = '$Course_ID' AND occupied_ID = '$occupied_ID' LIMIT 1";
			$roomQuery = mysqli_query($this->con, $roomString);
			$room = mysqli_fetch_assoc($roomQuery);
			//echo $room['roomName'];
			return "\n".$room['roomName'];
		}
		private function getDaysOfWeek($days)
		{
			$dow = [];
			if($days['M'] != 'no')
				$dow[] = 1;
			if($days['T'] != 'no')
				$dow[] = 2;
			if($days['W'] != 'no')
				$dow[] = 3;
			if($days['R'] != 'no')
				$dow[] = 4;
			if($days['F'] != 'no')
				$dow[] = 5;
			return $dow;
		}
		// method to return all the requesting classes
		public function giveRequestingClasses()
		{
			$string = "SELECT Week_ID FROM collision GROUP BY Week_ID ORDER BY COUNT(Week_ID) DESC";
			$query = mysqli_query($this->con,$string);
			$weekCourses = array();
			while($week = mysqli_fetch_assoc($query))
			{
				$string = "SELECT Course_ID, Room_ID, M, T, W, R, F FROM collision WHERE Week_ID =".$week['Week_ID']."";
				$courseQuery = mysqli_query($this->con, $string);
				while($row = mysqli_fetch_assoc($courseQuery))
				{
					$weekCourses[$week['Week_ID']] [] = $row;
				}
			}
			return $weekCourses;
		}
        // method to give the start and end date of a week given a week id
		public function giveWeekStartEnd($week_id, $semester_id)
		{
			$string = "SELECT start_date, end_date FROM week WHERE ID = '$week_id' AND semester_id = '$semester_id'";
			$query = mysqli_query($this->con, $string);
			$dates = mysqli_fetch_assoc($query);
			return $dates;
		}
        // method to get the latest semester
        public function getLatestSem()
		{
			$string = "SELECT ID, MAX(end_date) FROM semester LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$sem = mysqli_fetch_assoc($query);
			return $sem['ID'];
		}
		// method to give the name of a room given a room id
		public function giveRoomName($room_id)
		{
			$string = "SELECT Name FROM room WHERE ID = '$room_id'";
			$query = mysqli_query($this->con,$string);
			$name = mysqli_fetch_assoc($query);
			return $name['Name'];
		}
		public function giveStudents()
		{
			$students = array();
			$string = "SELECT CWID, Fname, Lname, email FROM person WHERE role = 'student'";
			$query = mysqli_query($this->con, $string);
			while($student = mysqli_fetch_assoc($query))
			{
				$oneStudent = array();
				$oneStudent['CWID'] = $student['CWID'];
				$oneStudent['Fname'] = $student['Fname'];
				$oneStudent['Lname'] = $student['Lname'];
				$oneStudent['email'] = $student['email'];
				$students[] = $oneStudent; 
			}
			return $students;
		}
		public function getEmail($cwid, $privilege)
		{
			$string = "SELECT email FROM person WHERE CWID = '$cwid' AND role = '$privilege'";
			$query = mysqli_query($this->con, $string);
			$person = mysqli_fetch_assoc($query);
			return $person['email'];
		}
		public function giveTeachers()
		{
			$teachers = array();
			$string = "SELECT CWID, Fname, Lname, email FROM person WHERE role = 'teacher'";
			$query = mysqli_query($this->con, $string);
			while($teacher = mysqli_fetch_assoc($query))
			{
				$oneTeacher = array();
				$oneTeacher['CWID'] = $teacher['CWID'];
				$oneTeacher['Fname'] = $teacher['Fname'];
				$oneTeacher['Lname'] = $teacher['Lname'];
				$oneTeacher['email'] = $teacher['email'];
				$teachers[] = $oneTeacher; 
			}
			return $teachers;
		}
        public function setSemesterDates($id,$name, $start_date, $end_date)
		{
			if($end_date < $start_date)
			{
				return false;
			}
			$string = "INSERT INTO semester(ID, semester, start_date, end_date) VALUES('$id','$name','$start_date','$end_date')";
			$query = mysqli_query($this->con, $string);
			$this->updateLog("Added semester start date as $startdate and end date as $end_date ", "N/A", "N/A", "N/A");
			$this->fillWeeks($id,$start_date,$end_date);
		}
        // to fill the weeks week and semester
		private function fillWeeks($Semester_ID, $sem_start_date, $sem_end_date)
		{
			$startWeek=date("W",strtotime($sem_start_date));
			$year=date("Y",strtotime($sem_start_date));
			$endWeek = date("W",strtotime($sem_end_date));
			while($startWeek > $endWeek)
			{
			    $result = $this->getWeekDates($startWeek,$year);
			    $week_start_date = $result['start'];
			    $week_end_date = $result['end'];
			    $string = "INSERT INTO week(ID, semester_ID, start_date, end_date) VALUES('$startWeek', '$Semester_ID', '$week_start_date', '$week_end_date')";
			    $query = mysqli_query($this->con, $string);

			    $startWeek++;
			    if($startWeek > 52)
			    {
			        $startWeek = 1;
			        $year += 1;
			    }
			}
			for($i=$startWeek;$i<=$endWeek;$i++) {
			    $result=$this->getWeekDates($i,$year);
			    $week_start_date = $result['start'];
			    $week_end_date = $result['end'];
			    $string = "INSERT INTO week(ID, semester_ID, start_date, end_date) VALUES('$i', '$Semester_ID', '$week_start_date', '$week_end_date')";
			    $query = mysqli_query($this->con, $string);
			}
		}
        private function getWeekDates($week, $year) {
		  $dto = new DateTime();
		  $result['start'] = $dto->setISODate($year, $week, 0)->format('Y-m-d');
		  $result['end'] = $dto->setISODate($year, $week, 6)->format('Y-m-d');
		  return $result;
		}

		public function giveClasses()
		{
			$semester = $this->getLatestSem();
			$clases = array();	
			$sql = "SELECT DISTINCT Prefix, Number, Course_ID, Title FROM course WHERE Semester_ID = $semester;";
			$res = mysqli_query($this->con, $sql);

				while($class = mysqli_fetch_assoc($res)){
					$oneClass = array();
					$oneClass['Prefix'] = $class['Prefix'];
					$oneClass['Number'] = $class['Number'];
					$oneClass['Course_ID'] = $class['Course_ID'];
					$oneClass['Title'] = $class['Title'];

					$clases[] = $oneClass;
				}
			return $clases;
		}
		
		public function generateOptions($prefix, $number)
		{
			$teachers = array();
			$sql = "SELECT Fname, Lname FROM course c join person p on p.CWID = c.Teacher_CWID where Prefix = '$prefix' and Number = '$number'";
			$res = mysqli_query($this->con, $sql);

			while ($teacher = mysqli_fetch_array($res, MYSQLI_ASSOC)) {

				$oneTeacher = array();	
				
     			$oneTeacher['Fname'] = $teacher['Fname'];
     			$oneTeacher['Lname'] = $teacher['Lname'];
     			
     			$teachers[] = $oneTeacher;
    		}
    		return $teachers;
		}

		public function displayLeaders()
		{
			$leaders = array();
			$sql = "SELECT DISTINCT c.Prefix, c.Number, p.Fname, p.Lname, p.CWID from person as p join course as c on p.CWID = c.Lead_teacher where c.Semester_ID = 1;";
			$res = mysqli_query($this->con, $sql);

			while($lead = mysqli_fetch_array($res, MYSQLI_ASSOC)) {

				$oneLeader = array();

				$oneLeader['Prefix'] = $lead ['Prefix'];
				$oneLeader['Number'] = $lead ['Number'];
				$oneLeader['Fname'] = $lead ['Fname'];
				$oneLeader['Lname'] = $lead ['Lname'];
				$oneLeader['CWID'] = $lead ['CWID'];

				$leaders[] = $oneLeader;
			}
			return $leaders;
		}


		/*************************************************************
			the regestration buton display
		**************************************************************/
		
		//Method to determine what is the regestration status and siplay regestration button in the nav bar
      public function regBtn(){

			$setting;
			$open = "yes";
			$close = "no";
			$semester = $this->getLatestSem();

			$sql = "SELECT register_permission FROM semester WHERE ID = $semester";

			$result = mysqli_query($this->con, $sql);

			  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

			          $setting = $row['register_permission'];


			if($setting == $open){

			  echo " <input type='button' class = 'btn btn-success' data-toggle = 'modal' data-target = '#myModal' value = 'Registration Open'>  ";
			}

			elseif($setting == $close){

				$date = date("Y-m-d");
				$sql2 = "UPDATE semester SET deadline = '$date' WHERE ID = $semester;";

				mysqli_query($this->con, $sql2);

				echo " <input type='button' class = 'btn btn-danger' data-toggle = 'modal' data-target = '#myModal' value = 'Registration Closed'> ";
				
			}    


		}	

		/***************************************************************
		Functions that have been added for notes related purposes
		***************************************************************/
		
		public function roomsButton(){			

			$rooms = array();
			$sql = "SELECT Name, ID FROM room WHERE Available = 'yes';";

			$res = mysqli_query($this->con, $sql);

			while($room = mysqli_fetch_array($res, MYSQLI_ASSOC)) {

				$oneRoom = array();

				$oneRoom['Name'] = $room['Name'];
				$oneRoom['ID'] = $room['ID'];

				$rooms [] = $oneRoom;

			}	

			return $rooms;
		}

		//Function to compare the deadline date and current date
		public function deadlineCheck(){

			$today =  date('Y-m-d');
			$semester = $this->getLatestSem();

			$sql = "SELECT deadline from semester where ID = $semester;";

			$res = mysqli_fetch_array(mysqli_query($this->con, $sql), MYSQLI_ASSOC);



			$deadline = $res['deadline'];

			
			if($today < $deadline){
				return true;
			}
			else{

				return false;
			}
		}

		public function weeks(){
			
					
			$weeks = array();	
			$semester = $this->getLatestSem();
			$sql = "SELECT ID, start_date, end_date FROM week WHERE semester_ID = $semester";
			$res = mysqli_query($this->con, $sql);

				while($week = mysqli_fetch_assoc($res)){

					$oneWeek = array();

					$oneWeek['ID'] = $week['ID'];
					$oneWeek['start_date'] = $week['start_date'];
					$oneWeek['end_date'] = $week['end_date'];
					

					$weeks[] = $oneWeek;

				}
			return $weeks;
		}

		public function getName($email){

			$string = "SELECT Fname, Lname from person where email = '$email';";
			$query = mysqli_query($this->con,$string);			
			$result = mysqli_fetch_assoc($query);

			$name = array();
			$name[] = $result['Fname'];
			$name[] = $result['Lname'];
			return $name;
		}

		public function checkNote($semester, $course_id, $week_id, $name){

			$status = 0;
			$string = "SELECT * FROM notes where Semester_ID = $semester and Course_ID = $course_id and Week_ID = $week_id and Name = '$name'";
			

			$query = mysqli_query($this->con,$string);			
			$result = mysqli_fetch_assoc($query);

			if(!empty($result)){

				$status = 1;
				

			}
			else{
				$status = 0;
				
			}			

			return $status;

		}

		public function dispalyMyNotes($email, $courseID){


			$notes = array();	
			$semester = $this->getLatestSem();
			$sql = "SELECT Note, Name, Course_ID, Week_ID FROM `notes` WHERE Course_ID = $courseID and Semester_ID = $semester";
			$res = mysqli_query($this->con, $sql);

				while($note = mysqli_fetch_assoc($res)){

					$oneNote = array();

					$oneNote['Note'] = $note['Note'];
					$oneNote['Name'] = $note['Name'];
					$oneNote['Course_ID'] = $note['Course_ID'];
					$oneNote['Week_ID'] = $note['Week_ID'];
					

					$notes[] = $oneNote;

				}
			return $notes;
		}
}
?>