<?php
	class Teacher
	{
		private $con;
		private $email;
		public function __construct($con, $email)
		{
			$this->con = $con;
			$this->email = $_SESSION['email'];
		}
		public function myClasses()
		{
			$string = "SELECT Course_ID, Prefix, Number, Title FROM course, teacher WHERE course.Teacher_CWID = teacher.CWID AND teacher.email = '$this->email'";
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
			$databaseQuery = "SELECT Course_ID as id, Number, Title as title
			FROM course, person WHERE person.CWID = course.Teacher_CWID AND person.email = '$this->email'";
			$result = mysqli_query($this->con, $databaseQuery);
			//query to get the start and the end date of the semester, max(id) will g
			// give the semester with the highest id,i.e. the last input value in the table or the lastest semester added to the table.
			$dates = mysqli_query($this->con, "SELECT MAX(id), start_date, end_date FROM semester");
			$dateRows = mysqli_fetch_assoc($dates);
			// iterate through the classes taught by the teacher
			while($row = mysqli_fetch_assoc($result))
			{
				$Course_ID = $row['id'];
				// query to get the days and class time for a class
				$courseDay = mysqli_query($this->con, "SELECT M, T, W, R, F, Start_time, End_time FROM course WHERE Course_ID = '$Course_ID'");
				$days = mysqli_fetch_assoc($courseDay);
				$dow = $this->getDaysOfWeek($days);
				$row['dow'] = $dow;
				$row['dowstart'] = date('Y-m-d', strtotime($dateRows['start_date']));
				$row['dowend'] = date('Y-m-d', strtotime($dateRows['end_date']));
				$row['start'] = $days['Start_time'];
				$row['end'] = $days['End_time'];
				$data[] = $row;
			}
			//echo(json_encode($data));
			//print_r($data);
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
		public function createEvent()
		{
			
		}
	}
?>