<?php
	class Room
	{
		private $id;
		private $con;
		public function __construct($con,$id)
		{
			$this->id = $id;
			$this->con = $con;
		}
		public function showRooms($course_id)
		{
			$startTime = $this->getStartTime($course_id);
			$endTime = $this->getEndTime($course_id);
		}
		private function getStartTime($course_id)
		{
			$string = "SELECT Start_time FROM course WHERE Course_id = '$course_id' LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			return $row[0];
		}
		private function getEndTime($course_id)
		{
			$string = "SELECT End_time FROM course WHERE Course_id = '$course_id' LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			return $row[0];
		}
	}
?>