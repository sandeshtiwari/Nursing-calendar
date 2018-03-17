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
		public function getOccupiedRoomAndDays($course_id,$semester_id,$weeks)
		{
			$startTime = $this->getStartTime($course_id,$semester_id);
			$endTime = $this->getEndTime($course_id,$semester_id);
			$daysOfWeek = $this->getDaysOfWeek($course_id, $semester_id);
			//echo 'here';
			//print_r($daysOfWeek);
			$occupiedDays = array();
			foreach($weeks as $week)
			{
				//echo "SELECT Room_ID, Course_ID FROM occupied WHERE Semester_ID = '$semester_id' AND Week_ID='$week'";
				$string = "SELECT Room_ID, Course_ID FROM occupied WHERE Semester_ID = '$semester_id' AND Week_ID='$week'";
				$query = mysqli_query($this->con,$string);
				//$id = mysqli_fetch_assoc($query);
				//print_r($id);
				while( $id = mysqli_fetch_assoc($query))
				{
					// getting the days which are occupied for a week, i.e. for $week
					$daysOccupied = $this->getDaysOccupiedOfWeek($id['Course_ID'], $semester_id, $week);
					// getting the days that are conflicting for the given course for a certain week for a seleted room
					$conflictingDays = $this->getAllCommonOccupiedDays($daysOfWeek,$daysOccupied);
					//print_r($conflictingDays);
					// if there are days that are conflicting
					if(!empty($conflictingDays))
					{
						$cStartTime = $this->getStartTime($id['Course_ID'], $semester_id);
						$cEndTime = $this->getEndTime($id['Course_ID'], $semester_id);
						// if the time is also conflicting then store the value as the conflicting day and key as the room
						if(($cStartTime >= $startTime && $cStartTime <= $endTime) || ($cEndTime >= $startTime && $cEndTime <= $endTime))
						{
							// if some days are already conflicting for a room using temp to append to the existing one
							if(isset($occupiedDays[$id['Room_ID']]))
							{
								$temp = $occupiedDays[$id['Room_ID']];
							}
							else
							{
								$temp = array();
							}
							// storing the key as the room which is conflicting and value as the days that are conflicting
							$occupiedDays[$id['Room_ID']] = array_merge($temp, $conflictingDays);
							// once a conflict is found for a room for a given week, break the loop and search for other weeks' conflicts 
							break;
						}
					}
				}
			}
			//print_r($occupied);
			return $occupiedDays;
		}
        // method to get the intersection days between weeks
		private function getAllCommonOccupiedDays($week1, $week2)
		{
			$intersection = array_intersect_assoc($week1, $week2);
			foreach($intersection as $day=>$value)
			{
				if($value == 'no')
					unset($intersection[$day]);
			}
			return $intersection;
		}
        public function getRoomFromKeys($array)
		{
			$rooms = array();
			foreach($array as $room => $day)
			{
				$rooms[] = $room;
			}
			return $rooms;
		}
        // method to give the days booked for a certain week for a ceratin room
		private function getDaysOfWeekRoom($room_id, $week)
		{
			$string = "SELECT M, T, W, R, F FROM occupied WHERE Room_ID = '$room_id' AND Week_ID = '$week'";
			$query = mysqli_query($this->con, $string);
			$daysBooked = array();
			$daysBooked['M'] = 'no';
			$daysBooked['T'] = 'no';
			$daysBooked['W'] = 'no';
			$daysBooked['R'] = 'no';
			$daysBooked['F'] = 'no';
			//echo "here";
			//print_r($daysBooked);			
			while($classBookedDays = mysqli_fetch_assoc($query))
			{
				//print_r($classBookedDays);
				if($classBookedDays['M'] == 'yes' && $daysBooked['M'] == 'no')
				{
					$daysBooked['M'] = 'yes';
					//echo $daysBooked['M'];
				}
				if($classBookedDays['T'] == 'yes' && $daysBooked['T'] == 'no')
				{
					$daysBooked['T'] = 'yes';
					//echo $daysBooked['T'];
				}
				if($classBookedDays['W'] == 'yes' && $daysBooked['W'] == 'no')
				{
					$daysBooked['W'] = 'yes';
					//echo $daysBooked['W'];
				}
				if($classBookedDays['R'] == 'yes' && $daysBooked['R'] == 'no')
				{
					$daysBooked['R'] = 'yes';
					//echo $daysBooked['R'];
				}
				if($classBookedDays['F'] == 'yes' && $daysBooked['F'] == 'no')
				{
					$daysBooked['F'] = 'yes';
					//echo $daysBooked['M'];
				}
			}
			//print_r($daysBooked);
			return $this->sortWeek($daysBooked);
		}
        // method to get an array with the weeks as per the given start-date and end-date for a given semester
		public function getWeeksArray($start_date, $end_date,$semester)
		{
			$string = "SELECT ID as week_id FROM week WHERE start_date >= '$start_date' AND end_date <= '$end_date' AND semester_ID = '$semester'";
			$query = mysqli_query($this->con, $string);
			$weeks = array();
			while($id = mysqli_fetch_assoc($query))
			{
				$weeks[] = $id['week_id'];
			}
			return $weeks;
		}
        // method to get the days of week for which a course is assigned for a given semester
		public function getDaysOfWeek($course_id,$semester_id)
		{
			$string = "SELECT M, T, W, R, F FROM course WHERE Course_ID = '$course_id' AND Semester_ID='$semester_id' LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$rows = array();
			$row = mysqli_fetch_assoc($query);
			return $row;
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
		// method to book a room for a course
		public function reserveRoom($room_id, $course_id)
		{
			$latestSem = $this->getLatestSem();
			$string = "INSERT INTO occupied(Course_ID, Room_ID, Semester_ID) VALUES('$course_id', '$room_id', '$latestSem')";
			$query = mysqli_query($this->con, $string);
			return $query;
		}
		private function getLatestSem()
		{
			$string = "SELECT ID, MAX(end_date) FROM semester LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$sem = mysqli_fetch_assoc($query);
			return $sem['ID'];
		}
		// function to check if already booked for a course
		public function checkBookStatus($course_id, $room_id)
		{
			//echo $course_id." ".$room_id;
			$string = "SELECT * FROM occupied WHERE Course_ID='$course_id' AND Room_ID = '$room_id'";
			$query = mysqli_query($this->con, $string);
			//$rows = mysqli_fetch_assoc($query);
			if(mysqli_num_rows($query) > 0)
			{
				return true;
			}
			return false;
		}
		// function to add collision
		public function addCollision($course_id, $room_id)
		{
			$startTime = $this->getStartTime($course_id);
			$endTime = $this->getEndTime($course_id);
			$daysOfWeek = $this->getDaysOfWeek($course_id);
			//echo strtotime($startTime);
			$vacant = array();
			$collidingCourses = array();
			$string = "SELECT Course_ID, end_date FROM occupied,semester WHERE Course_ID != '$course_id' AND Room_ID = '$room_id'
			AND end_date = (SELECT MAX(end_date) FROM semester)";
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
						$collidingCourses[] = $id['Course_ID'];
						//echo 'here';
					}
				}
			}
			print_r($collidingCourses);
			$Coll_ID = $this->getUniqueCollID();
			$string = "INSERT INTO collision(Course_ID, Coll_ID, Room_ID) VALUES('$course_id','$Coll_ID', '$room_id')";
				mysqli_query($this->con, $string);
 			foreach($collidingCourses as $col_courses)
			{
				$string = "INSERT INTO collision(Course_ID, Coll_ID, Room_ID) VALUES('$col_courses','$Coll_ID', '$room_id')";
				mysqli_query($this->con, $string);
			}
			//return $occupied;
		}
		// function to get a unique collision id which is not already in the table
		private function getUniqueCollID()
		{
			$string = "SELECT FLOOR(RAND() * 99999) AS Coll_ID 
			FROM occupied 
			WHERE 'Coll_ID' NOT IN (SELECT Coll_ID FROM collision) LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			return $row['Coll_ID'];
		}
		// function to cancle registration for a course
		public function cancelRegistration($course_id, $room_id)
		{
			$string = "DELETE FROM occupied WHERE Course_ID = '$course_id' AND Room_ID='$room_id'";
			mysqli_query($this->con, $string);
		}
		// method to check if a room is already requested for a course
		public function checkRequested($course_id, $room_id)
		{
			$string = "SELECT Course_ID FROM collision WHERE Course_ID = '$course_id' AND Room_ID = '$room_id'";
			$query = mysqli_query($this->con, $string);
			if(mysqli_num_rows($query) > 0)
			{
				return true;
			}
			return false;

		}
	}
?>