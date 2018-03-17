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
				$row['title'] = $Course_ID." ".$row['title'];
				$row['dow'] = $dow;
				$row['dowstart'] = date('Y-m-d', strtotime($dateRow['start_date']));
				$row['dowend'] = date('Y-m-d', strtotime($dateRow['end_date']));
				$row['start'] = $days['Start_time'];
				$row['end'] = $days['End_time'];
				$data[] = $row;
			}
			return json_encode($data);
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
		// method to  return an array with the rooms with collisions and collisions
		public function giveCollisions()
		{
			$string = "SELECT DISTINCT Coll_ID, Room_ID FROM collision";
			$query = mysqli_query($this->con,$string);
			$allCols = array();
			// going over each collisions
			while($collisions = mysqli_fetch_assoc($query))
			{
				$coll_id = $collisions['Coll_ID'];
				// selecting courses with a particular collision id
				$string = "SELECT course.Course_ID as Course_ID, Prefix, Number, Title FROM collision,course WHERE collision.Course_ID = course.Course_ID AND Coll_ID = '$coll_id'";
				$courseQuery = mysqli_query($this->con, $string);
				$courses = array();
				// going over courses which are colliding
				while($course = mysqli_fetch_assoc($courseQuery))
				{
					$collDetails = array();
					$collDetails['Course_ID'] = $course['Course_ID'];
					$collDetails['Prefix'] = $course['Prefix'];
					$collDetails['Number'] = $course['Number'];
					$collDetails['Title'] = $course['Title'];
					$courses[] = $collDetails;
				}
				$allCols[$collisions['Room_ID']] = $courses;
			}
			return $allCols;
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
}
?>