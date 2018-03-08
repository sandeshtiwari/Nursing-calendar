<?php
	class Student
	{
		private $con;
		private $email;
		public function __construct($con, $email)
		{
			$this->con = $con;
			$this->email = $_SESSION['email'];
		}
		public function getJSON()
		{
			$data = array();
			$databaseQuery = "SELECT course.Course_ID as id, Number, Title as title 
			FROM course, registered, person WHERE 
			course.Course_ID = registered.Course_ID AND registered.CWID = person.CWID AND person.email = '$this->email'";
			$result = mysqli_query($this->con, $databaseQuery);
			while($row = mysqli_fetch_assoc($result))
			{
				$Course_ID = $row['id'];
				// query to get the days and class time for a class
				$courseDay = mysqli_query($this->con, "SELECT M, T, W, R, F, Start_time, End_time FROM course WHERE Course_ID = '$Course_ID'");
				$days = mysqli_fetch_assoc($courseDay);
				$dow = $this->getDaysOfWeek($days);
				$dates = mysqli_query($this->con, "SELECT start_date, end_date FROM semester, course WHERE semester.ID = course.Semester_ID AND course.Course_ID = '$Course_ID'");
				$dateRow = mysqli_fetch_assoc($dates);
				$roomString = "SELECT room.Name as roomName FROM room, occupied WHERE room.ID = occupied.Room_ID AND Course_ID = '$Course_ID'";
				$roomQuery = mysqli_query($this->con, $roomString);
				$room = mysqli_fetch_assoc($roomQuery);
				$row['title'] .= "\n".$room['roomName'];
				$row['dow'] = $dow;
				$row['dowstart'] = date('Y-m-d', strtotime($dateRow['start_date']));
				$row['dowend'] = date('Y-m-d', strtotime($dateRow['end_date']));
				$row['start'] = $days['Start_time'];
				$row['end'] = $days['End_time'];
				$data[] = $row;
			}
			return json_encode($data);
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
	}
?>