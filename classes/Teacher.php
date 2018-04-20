<?php
	class Teacher
	{
		private $con;
		private $email;
		public function __construct($con, $email)
		{
			$this->con = $con;
			$this->email = $email;
		}
		public function myClasses()
		{
			$string = "SELECT Course_ID, Prefix, Number, Title 
			FROM course, person, semester 
			WHERE course.Lead_teacher = person.CWID AND semester.ID = course.Semester_ID AND person.email = '$this->email' AND
			end_date = (SELECT MAX(end_date) FROM semester)";
			$check_database_query = mysqli_query($this->con, $string);
			$rows = array();
			while($row = mysqli_fetch_assoc($check_database_query))
			{
				$rows[] = $row['Course_ID']. " ". $row['Prefix']." ". $row['Number']. " ". $row['Title'];
			}
			//$row = mysqli_fetch_assoc($check_database_query);
			//print_r($rows);
			return $rows;
		}
		public function getJSON()
		{
			$data = array();
			// query to find all the classes taught by the teacher logged in
			$databaseQuery = "SELECT DISTINCT occupied_ID, occupied.Course_ID as id, Number, Title as title
			FROM course, person, occupied WHERE person.CWID = course.Teacher_CWID AND occupied.Course_ID = course.Course_ID AND person.email = '$this->email'";
			$result = mysqli_query($this->con, $databaseQuery);
			// iterate through the classes taught by the teacher
			while($row = mysqli_fetch_assoc($result))
			{
				$Course_ID = $row['id'];
				$occupied_ID = $row['occupied_ID'];
				$dates = mysqli_query($this->con, "SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM occupied, week 
					WHERE occupied.Week_ID = week.ID AND occupied.Course_ID = '$Course_ID' AND occupied_ID = '$occupied_ID'");
				$dateRow = mysqli_fetch_assoc($dates);
				// query to get the days and class time for a class
				$courseDay = mysqli_query($this->con, "SELECT O.M, O.T, O.W, O.R, O.F, Start_time, End_time FROM course, occupied as O 
					WHERE O.Course_ID = course.Course_ID AND O.Course_ID = '$Course_ID' AND O.occupied_ID = '$occupied_ID'");
				$days = mysqli_fetch_assoc($courseDay);
				$dow = $this->getDaysOfWeek($days);
				// get the starting and ending dates for the course
				//$dates = mysqli_query($this->con, "SELECT start_date, end_date FROM semester, course WHERE semester.ID = course.Semester_ID AND course.Course_ID = '$Course_ID'");
				//$dateRow = mysqli_fetch_assoc($dates);
				$row['title'] .= $this->getRoomNames($Course_ID,$occupied_ID);
				$row['title'] = $Course_ID."\n".$row['title'];
				$row['dow'] = $dow;
				$row['dowstart'] = date('Y-m-d', strtotime($dateRow['start_date']));
				$row['dowend'] = date('Y-m-d', strtotime($dateRow['end_date']));
				$row['start'] = $days['Start_time'];
				$row['end'] = $days['End_time'];
				$data[] = $row;
			}
			//echo(json_encode($data));
			//print_r($data);
			return json_encode($data);
		}
        public function checkRegistrationStatus()
        {
            //return "fuck";
            $string = "SELECT register_permission FROM semester WHERE ID = 1";
            $result = mysqli_query($this->con, $string);
            $row = mysqli_fetch_assoc($result);
            //return $row['register_permission'];
            if($row['register_permission'] == "yes"){
                return true;
            }
            //if not then say registration closed and have link to calender.php
            else if($row['register_permission'] == "no"){
                return false;
            }    
        }
		private function getRoomNames($Course_ID,$occupied_ID)
		{
			//echo "here";
			$roomString = "SELECT room.Name as roomName FROM room, occupied WHERE room.ID = occupied.Room_ID AND Course_ID = '$Course_ID' AND occupied_ID = '$occupied_ID' LIMIT 1";
			$roomQuery = mysqli_query($this->con, $roomString);
			//$rooms = "";
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
        public function getLatestSem()
		{
			$string = "SELECT ID, MAX(end_date) FROM semester LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$sem = mysqli_fetch_assoc($query);
			return $sem['ID'];
		}
        public function getWeekDates($semester_id)
		{
			$string = "SELECT start_date, end_date FROM week WHERE semester_ID = '$semester_id'";
			$query = mysqli_query($this->con, $string);
			$returnDates = array();
			while($dates = mysqli_fetch_assoc($query))
			{
				$returnDates[] = $dates;
			}
			return $returnDates;
		}

		public function checkRegestrationPermission()
		{
			$open = "yes";
			$sql = "SELECT register_permission FROM semester WHERE ID = 1";
			$permission = false;
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$setting = $row['register_permission'];
				if($setting == $open){
					$permission = true;
				}
				return $permission;   
		}

		public function classesNow($id)
		{			
			$clases = array();	
			$semester = $this->getLatestSem();
			$sql = "SELECT DISTINCT Prefix, Number, Course_ID, Title  FROM course WHERE Semester_ID = $semester and Teacher_CWID = $id;";
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
		public function getID($email)
		{

			$sql = "SELECT DISTINCT CWID from person where email = '$email';";
			$res = mysqli_query($this->con, $sql);

			if ($id = mysqli_fetch_assoc($res)){
				$CWID = $id['CWID'];
				return $CWID;
			}
			else{
				echo "Mistake";
			}
		}


		public function giveBooked($courseID)
		{

			$classes = array();
			$sql = "SELECT Course_ID, Room_ID, Name, M, T, W, R, F, Week_ID, start_date, end_date, week_id 
					FROM occupied as o
						INNER JOIN  week as w on o.Week_ID = w.Id
					    INNER JOIN room as r on o.Room_ID = r.ID
					WHERE Course_ID = $courseID
					ORDER by start_date";
			$res = mysqli_query($this->con, $sql);

			while($class = mysqli_fetch_assoc($res)){

				$oneClass = array();
				$oneClass['Name'] = $class['Name'];

				$oneClass['M'] = $class['M'];
				$oneClass['T'] = $class['T'];
				$oneClass['W'] = $class['W'];
				$oneClass['R'] = $class['R'];
				$oneClass['F'] = $class['F'];

				$oneClass['start_date'] = $class['start_date'];
				$oneClass['end_date'] = $class['end_date'];
				$oneClass['week_id'] = $class['week_id'];
				$oneClass['Room_ID'] = $class['Room_ID'];
				$classes[] = $oneClass;

			}
		return $classes;
		}


		public function givePending($courseID)
		{

			$classes = array();
			$sql = "SELECT Course_ID, Room_ID, Name, M, T, W, R, F, Week_ID, start_date, end_date, week_id
					FROM collision as c 
						INNER JOIN week as w on c.Week_ID = w.Id 
						INNER JOIN room as r on c.Room_ID = r.ID 
					WHERE Course_ID = $courseID 
					ORDER by start_date";
					
			$res = mysqli_query($this->con, $sql);

			while($class = mysqli_fetch_assoc($res)){

				$oneClass = array();
				$oneClass['Name'] = $class['Name'];

				$oneClass['M'] = $class['M'];
				$oneClass['T'] = $class['T'];
				$oneClass['W'] = $class['W'];
				$oneClass['R'] = $class['R'];
				$oneClass['F'] = $class['F'];

				$oneClass['start_date'] = $class['start_date'];
				$oneClass['end_date'] = $class['end_date'];
				$oneClass['week_id'] = $class['week_id'];
				$oneClass['Room_ID'] = $class['Room_ID'];
				$classes[] = $oneClass;

			}
		return $classes;
		}


		public function giveWeekStartEnd($week_id, $semester_id)
		{
			$string = "SELECT start_date, end_date FROM week WHERE ID = '$week_id' AND semester_id = '$semester_id'";
			$query = mysqli_query($this->con, $string);
			$dates = mysqli_fetch_assoc($query);
			return $dates;
		}


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

		public function giveCollidingCourse($room_id, $week_id, $day, $semester_id)
		{
			$day = substr($day, 0, 1);
			$string = "SELECT Course_ID FROM occupied
			 WHERE Room_ID = $room_id AND Week_ID = $week_id AND Semester_ID = $semester_id AND $day = 'yes'";
			 $query = mysqli_query($this->con, $string);
			 $course = mysqli_fetch_assoc($query);
			 return $course['Course_ID'];
		}

		public function giveCourseName($course_id)
		{
			$string = "SELECT Prefix, Number, Title FROM course WHERE Course_ID = $course_id";
			$query = mysqli_query($this->con, $string);
			$course = mysqli_fetch_assoc($query);
			$courseDetails = $course['Prefix']." ".$course['Number']." ".$course['Title'];
			return $courseDetails;
		}
		
		public function giveRoomName($room_id)
		{
			$string = "SELECT Name FROM room WHERE ID = '$room_id'";
			$query = mysqli_query($this->con,$string);
			$name = mysqli_fetch_assoc($query);
			return $name['Name'];
		}
		/**************************************************************
		Function added for the button
		***********************************************************/
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

		/*****************************************************************************************
		Functions added for the notes
		***************************************************************************************/


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

			//$myName = $this->getName($email);
			//$FullName = $myName[0]." ".$myName[1];

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