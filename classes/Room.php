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
		// method to get all the occupied rooms IDs for a given course
		public function getOccupiedRooms($course_id)
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
			return $occupied;
			//$vacant = $this->giveFreeRooms($occupied);
			//print_r($vacant);
			//return $this->getAllRooms($vacant, $occupied);
		}
		// method to get all the properties of the rooms from the nursing building
		public function getRoomProperties()
		{
			$columns = array();
			$string = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='nursing_scratch' AND `TABLE_NAME`='room'";
			$query = mysqli_query($this->con, $string);
			while($result = mysqli_fetch_assoc($query))
			{
				$columns[] = $result['COLUMN_NAME'];
			}
			return $columns;
		}
		// method to get full details for a set of rooms of a given room's ID
		public function getFullRow($id)
		{
			//echo $id.length;
			for($i = 0; $i< sizeof($id);$i++)
			{
				//echo $id[$i];
				$string = "SELECT * FROM room WHERE ID='$id[$i]'";
				$query = mysqli_query($this->con, $string);
				$row = mysqli_fetch_array($query);
				$id[$i] = $row;
			}
			return $id;
		}
		// method to get all the vacant rooms with by using all the occupied rooms
		public function getVacantRooms($occupied)
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
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			//print_r($row);
			return $row;
		}
		public function reserveRoom($room_id, $course_id)
		{
			$string = "INSERT INTO occupied(Course_ID, Room_ID) VALUES('$course_id', '$room_id')";
			$query = mysqli_query($this->con, $string);
			return $query;
		}
	}
?>