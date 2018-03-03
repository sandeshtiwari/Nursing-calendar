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

		}
		private function getStartTime($course_id)
		{

		}
	}
?>