<?php
	class Room
	{
		//private $id;
		private $con;
		public function __construct($con)
		{
			//$this->id = $id;
			$this->con = $con;
		}
		public function showRooms($course_id)
		{
			//echo 'here';
			$startTime = $this->getStartTime($course_id);
			$endTime = $this->getEndTime($course_id);
			$daysOfWeek = $this->getDaysOfWeek($course_id);
			//echo strtotime($startTime);
			$vacant = array();
			$occupied = array();
			$string = "SELECT Room_ID, Course_ID FROM occupied WHERE Course_ID != '$course_id'";
			$query = mysqli_query($this->con,$string);
			while( $id = mysqli_fetch_assoc($query))
			{
				//print_r($id['Room_ID']);
				//echo $id['Room_ID']." ".$id['Course_ID'];
				if($daysOfWeek == ($this->getDaysOfWeek($id['Course_ID'])))
				{
					//echo 'here';
					$cStartTime = $this->getStartTime($id['Course_ID']);
					$cEndTime = $this->getEndTime($id['Course_ID']);
					if(($cStartTime >= $startTime && $cStartTime <= $endTime) || ($cEndTime >= $startTime && $cEndTime <= $endTime))
					{
						$occupied[] = $id['Room_ID'];
						//echo 'here';
					}
				}
			}
			//print_r($occupied);
			$vacant = $this->giveFreeRooms($occupied);
			//print_r($vacant);
		}
		private function giveFreeRooms($occupied)
		{
			$vacant = array();
			$string = "SELECT ID FROM room";
			$query = mysqli_query($this->con, $string);
			while($roomID = mysqli_fetch_assoc($query))
			{
				//echo $roomID['ID'];
				if(!in_array($roomID['ID'], $occupied))
				{
					$vacant[] = $roomID['ID'];
				}
			}
			return $vacant;
		}
		private function getStartTime($course_id)
		{
			$string = "SELECT Start_time FROM course WHERE Course_id = '$course_id' LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			//echo $row[0];
			//print_r($row);
			return $row['Start_time'];
		}
		private function getEndTime($course_id)
		{
			$string = "SELECT End_time FROM course WHERE Course_id = '$course_id' LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			return $row['End_time'];
		}
		private function getDaysOfWeek($course_id)
		{
			$string = "SELECT M, T, W, R, F FROM course WHERE Course_id = '$course_id'";
			$query = mysqli_query($this-con, $string);
			$row = mysqli_fetch_assoc($query);
			print_r($row);
			return $row;
		}
	}
?>