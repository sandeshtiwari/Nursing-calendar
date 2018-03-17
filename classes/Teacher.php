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
			WHERE course.Teacher_CWID = person.CWID AND semester.ID = course.Semester_ID AND person.email = '$this->email' AND
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
				$row['title'] = $Course_ID." ".$row['title'];
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
		private function getRoomNames($Course_ID)
		{
			$roomString = "SELECT room.Name as roomName FROM room, occupied WHERE room.ID = occupied.Room_ID AND Course_ID = '$Course_ID'";
			$roomQuery = mysqli_query($this->con, $roomString);
			$rooms = "";
			while($room = mysqli_fetch_assoc($roomQuery))
			{
				$rooms .= "\n".$room['roomName'];
			}
			return $rooms;
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