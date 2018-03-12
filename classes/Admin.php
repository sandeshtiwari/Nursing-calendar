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
			$databaseQuery = "SELECT Course_ID as id, Number, Title as title FROM course";
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
				$row['title'] .= $this->getRoomNames($Course_ID);
				$row['dow'] = $dow;
				$row['dowstart'] = date('Y-m-d', strtotime($dateRow['start_date']));
				$row['dowend'] = date('Y-m-d', strtotime($dateRow['end_date']));
				$row['start'] = $days['Start_time'];
				$row['end'] = $days['End_time'];
				$data[] = $row;
			}
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
}
?>