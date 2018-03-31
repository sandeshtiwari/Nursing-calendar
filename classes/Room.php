<?php
	class Room
	{
		//private $id;
		private $con;
		private $occupiedWeeks;
		public function __construct($con)
		{
			//$this->id = $id;
			$this->con = $con;
			$this->occupiedWeeks = array();
		}
		// method to get all the occupied rooms IDs for a given course
		public function getOccupiedRoomAndDays($course_id,$semester_id,$weeks)
		{
			$startTime = $this->getStartTime($course_id,$semester_id);
			$endTime = $this->getEndTime($course_id,$semester_id);
			$daysOfWeek = $this->getDaysOfWeek($course_id, $semester_id);
			//echo 'here';
			//print_r($daysOfWeek);
			foreach($weeks as $week)
			{
				//echo "SELECT Room_ID, Course_ID FROM occupied WHERE Semester_ID = '$semester_id' AND Week_ID='$week'";
				$string = "SELECT Room_ID, Course_ID FROM occupied WHERE Semester_ID = '$semester_id' AND Week_ID='$week'";
				$query = mysqli_query($this->con,$string);
				//$id = mysqli_fetch_assoc($query);
				//print_r($id);
				$weeksOccupied = array();
				while( $id = mysqli_fetch_assoc($query))
				{
					// getting the days which are occupied for a week, i.e. for $week
					$daysOccupied = $this->getDaysOccupiedOfWeek($id['Course_ID'], $semester_id, $week);
					// getting the days that are conflicting for the given course for a certain week for a seleted room
					//echo $id["Room_ID"]." ".$week." ";
					//print_r($daysOccupied);
					$conflictingDays = $this->getAllCommonOccupiedDays($daysOfWeek,$daysOccupied);
					//print_r($conflictingDays);
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
							//echo $id['Room_ID'];
							//print_r($occupiedDays);
							if(isset($weeksOccupied[$id['Course_ID']]) && !(in_array($id['Room_ID'], $weeksOccupied[$id['Course_ID']])))
                            {
                                $weeksOccupied[$id['Course_ID']][] = $id['Room_ID'];
                            }
                            else
                            {
                                $weeksOccupied[$id['Course_ID']] = array($id['Room_ID']);   
                            }    
						}
					}
				}
				$this->occupiedWeeks[$week] = $weeksOccupied;
			}
			return $occupiedDays;
		}
		// method to check if a room is already requested for a course
		public function checkRequested($course_id, $room_id, $semester_id, $weeks, $requestedDays)
		{
			//echo "here";
			$requestedDays = $this->getAllDaysFromRequestedDays($requestedDays);
			//print_r($weeks);
			//print_r($requestedDays);
			foreach($weeks as $week)
			{
				echo $week." ";
				$string = "SELECT M, T, W, R, F FROM collision 
				WHERE Course_ID = '$course_id' AND Room_ID = '$room_id' AND Semester_ID = '$semester_id' AND Week_ID = '$week'";
				$query = mysqli_query($this->con, $string);
				while($previouslyRequesteDays = mysqli_fetch_assoc($query))
				{
					print_r($previouslyRequesteDays);
					if($previouslyRequesteDays['M'] == 'yes' && $requestedDays['M'] == 'yes')
					{
						echo "here";
						return true;
					}
					else if($previouslyRequesteDays['T'] == 'yes' && $requestedDays['T'] == 'yes')
					{
						return true;
					}
					if($previouslyRequesteDays['W'] == 'yes' && $requestedDays['W'] == 'yes')
					{
						return true;
					}
					if($previouslyRequesteDays['R'] == 'yes' && $requestedDays['R'] == 'yes')
					{
						return true;
					}
					if($previouslyRequesteDays['F'] == 'yes' && $requestedDays['F'] == 'yes')
					{
						return true;
					}
				}
			}
			return false;
		}
		// function to add collision
        public function addCollision($room_id, $course_id, $semester_id, $weeks, $requestedDays)
        {
	        $collision_id = $this->getUniqueCollID();
	        $requestedDays = $this->getAllDaysFromRequestedDays($requestedDays);
	        //print_r($weeks);
	        //print_r($conflictingWeeks);
	        //print_r($conflictingCourses);
	        foreach($weeks as $week)
	        {
	        	//echo $week." ";
	        	$string = "INSERT INTO collision(Course_ID, Coll_ID, Room_ID,M,T,W,R,F,Week_ID,Semester_ID)
	        	VALUES('$course_id','$collision_id','$room_id','".$requestedDays['M']."','".$requestedDays['T']."','".$requestedDays['W']."','".$requestedDays['R']."','".$requestedDays['F']."','$week','$semester_id')";
	        	$query = mysqli_query($this->con,$string);
	        }
	        /*for($i = 0; $i<sizeof($conflictingCourses); $i++)
	        {
	        	$days = $this->getDaysOccupiedOfWeek($conflictingCourses[$i], $semester_id, $conflictingWeeks[$i]);
	        	//echo $conflictingWeeks[$i]." ".$semester_id." ".$conflictingCourses[$i];
	        	$string = "INSERT INTO collision(Course_ID, Coll_ID, Room_ID,M,T,W,R,F,Week_ID,Semester_ID,booked)
	        	VALUES('".$conflictingCourses[$i]."','$collision_id','$room_id','".$days['M']."','".$days['T']."','".$days['W']."','".$days['R']."','".$days['F']."','".$conflictingWeeks[$i]."','$semester_id','yes')";
	        	$query = mysqli_query($this->con, $string);
	        }*/
        }
        // method to get all the days from a set of requested days
        private function getAllDaysFromRequestedDays($requestedDays)
        {
        	$allDays = array();
        	$allDays['M'] = 'no';
        	$allDays['T'] = 'no';
        	$allDays['W'] = 'no';
        	$allDays['R'] = 'no';
        	$allDays['F'] = 'no';
        	foreach($requestedDays as $requestedDay)
        	{
        		$allDays[$requestedDay] = 'yes';
        	}
        	return $allDays;
        }
		// method to check if a course has already booked a day for a room. This will be used to display only the days that are requestable
		public function checkBookedBySameClass($course_id, $semester_id, $weeksToBook, $day,$occupiedRoom)
        {
            //print_r($weeksToBook);
            foreach($weeksToBook as $week)
            {
                //echo $week." ";
                // echo 'here';
                //echo "SELECT Room_ID FROM occupied WHERE Course_ID = '$course_id' AND Semester_ID = '$semester_id' AND Week_ID = '$week' AND `$day` = 'yes'";
                $string = "SELECT Room_ID FROM occupied WHERE Course_ID = '$course_id' AND Semester_ID = '$semester_id' AND Week_ID = '$week' AND $day = 'yes' AND Room_ID='$occupiedRoom'";
                $query = mysqli_query($this->con, $string);
                $row = mysqli_fetch_assoc($query);
                //print_r($row);
                if(!empty($row))
                {
                    return true;
                }
            }
            return false;
        }
		// method to check if all the rooms are occupied by the class that is trying to book a room
        public function checkSelfOccupied($course_id, $room)
        {
            $weeks = $this->getOccupiedWeekClassRoom();
            $countTrues = 0;
            $countCourses = 0;
            //echo $course_id;
            foreach($weeks as $week)
            {
                //print_r($week);
                if(!empty($week))
                {
                    foreach($week as $course => $rooms)
                    {
                    	//echo "here";
                    	if(in_array($room, $rooms))
                    	{
                    		//echo $course." ";
	                        $countCourses++;
	                        //echo $course;
	                        if($course == $course_id)
	                        {
	                            $countTrues++;
	                        }
                    	}
                    }
                }
            }
            if($countTrues == $countCourses)
                return true;
            return false;
        }
        // method to get the occupied weeks with all the classes and the rooms they've occupied
        public function getOccupiedWeekClassRoom()
		{
			//print_r($this->occupiedWeeks);
			return $this->occupiedWeeks;
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
		public function getVacantRooms($occupied, $semester_id)
		{
			$vacant = array();
			$string = "SELECT ID FROM room";
			$query = mysqli_query($this->con, $string);
			$vacantRooms = array();
			while($roomID = mysqli_fetch_assoc($query))
			{
				//echo $roomID['ID'];
				if(!in_array($roomID['ID'], $occupied))
				{
					$days = $this->getOccupiedDaysForRoom($roomID['ID'], $semester_id);
					//echo $roomID['ID'] . " ";
					$vacant[$roomID['ID']] = $days;
				}
				$vacantRooms[] = $vacant;
			}
			return $vacant;
		}
        // method to get all the occupied days for a room in a given semester
		private function getOccupiedDaysForRoom($room_id, $semester_id)
		{
			$string = "SELECT Room_ID, M, T, W, R, F FROM occupied WHERE Room_ID = '$room_id' AND Semester_ID = '$semester_id'";
			$query = mysqli_query($this->con, $string);
			$days = array();
			while($row = mysqli_fetch_assoc($query))
			{
				if($row['M'] == 'yes' && !in_array('M', $days))
				{
					$days['M'] = 'yes';
				}
				if($row['T'] == 'yes' && !in_array('T', $days))
				{
					$days['T'] = 'yes';
				}
				if($row['W'] == 'yes' && !in_array('W', $days))
				{
					$days['W'] = 'yes';
				}
				if($row['R'] == 'yes' && !in_array('R', $days))
				{
					$days['R'] = 'yes';
				}
				if($row['F'] == 'yes' && !in_array('F', $days))
				{
					$days['F'] = 'yes';
				}
			}
			if(!isset($days['M']))
			{
				$days['M'] = 'no';
			}
			if(!isset($days['T']))
			{
				$days['T'] = 'no';
			}
			if(!isset($days['W']))
			{
				$days['W'] = 'no';
			}
			if(!isset($days['R']))
			{
				$days['R'] = 'no';
			}
			if(!isset($days['F']))
			{
				$days['F'] = 'no';
			}
			return $this->sortWeek($days);
		}
        // method to sort a given array with weekdays in ascending order
		private function sortWeek($days)
		{
			$sortedWeek = array('M','T','W','R','F');
			$finalWeekArray = array();
			//A simple loop that traverses all elements of the template...
			foreach($sortedWeek as $day)
			{
			    //If the value in the template exists as a key in the actual array.. (condition)
			    if(array_key_exists($day,$days))
			    {
			        $finalWeekArray[$day]=$days[$day]; //The value is assigned to the new array and the key of the actual array is assigned as a value to the new array
			    }
			}
			return $finalWeekArray;
		}
        // method to get the rooms that are not occupied after setting the values to 'no' if needed for displaying preoccupied rooms
		public function fillArrayWithDays($weeks)
		{
			$filledWeeks['M'] = 'no';
			$filledWeeks['T'] = 'no';
			$filledWeeks['W'] = 'no';
			$filledWeeks['R'] = 'no';
			$filledWeeks['F'] = 'no';
			if(isset($weeks['M']))
			{
				$filledWeeks['M'] = 'yes';
			}
			if(isset($weeks['T']))
			{
				$filledWeeks['T'] = 'yes';
			}
			if(isset($weeks['W']))
			{
				$filledWeeks['W'] = 'yes';
			}
			if(isset($weeks['R']))
			{
				$filledWeeks['R'] = 'yes';
			}
			if(isset($weeks['F']))
			{
				$filledWeeks['F'] = 'yes';
			}
			return $this->sortWeek($filledWeeks);
		}
        public function checkVacancy($occupiedDays, $course_id, $semester_id)
		{
			//echo $semester_id."          ".$course_id;
			$courseDays = $this->getDaysOfWeek($course_id, $semester_id);
			//print_r($courseDays);
			$days['M'] = 'no';
			$days['T'] = 'no';
			$days['W'] = 'no';
			$days['R'] = 'no';
			$days['F'] = 'no';
			
			if(isset($occupiedDays['M']))
			{
				$days['M'] = 'yes';
			}
			if(isset($occupiedDays['T']))
			{
				$days['T'] = 'yes';
			}
			if(isset($occupiedDays['W']))
			{
				$days['W'] = 'yes';
			}
			if(isset($occupiedDays['R']))
			{
				$days['R'] = 'yes';
			}
			if(isset($occupiedDays['F']))
			{
				$days['F'] = 'yes';
			}
			//print_r($days);
			if($days == $courseDays)
			{
				return false;
			}
			return true;
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
		// method to get the occupied days for a course in the given week for a given semester
		private function getDaysOccupiedOfWeek($course_id, $semester_id, $week)
		{
			$string = "SELECT M, T, W, R, F FROM occupied WHERE Course_ID = '$course_id' AND Semester_ID = '$semester_id' AND Week_ID = '$week'";
			$query = mysqli_query($this->con, $string);
			$occupiedDays = array();
			$occupiedDays['M'] = 'no';
			$occupiedDays['T'] = 'no';
			$occupiedDays['W'] = 'no';
			$occupiedDays['R'] = 'no';
			$occupiedDays['F'] = 'no';
			while($week = mysqli_fetch_assoc($query))
			{
				//print_r($week);
				foreach($week as $day => $occupiedOrNot)
				{
					if($occupiedOrNot == 'yes')
					{
						$occupiedDays[$day] = 'yes';
					}
				}
			}
			
			//print_r($occupiedDays);
			return $occupiedDays;
		}
		// method to book a room for a course on particular days of a week
		public function reserveRoom($room_id, $course_id, $semester_id, $weeks, $daysOfWeek)
		{
			//echo "here";
			$occupied_id = $this->getUniqueOccupiedID();
			$days['M'] = "no";
			$days['T'] = "no";
			$days['W'] = "no";
			$days['R'] = "no";
			$days['F'] = "no";
			foreach($daysOfWeek as $day)
			{
				$days[$day] = 'yes';
			}
			echo $occupied_id;
			print_r($days);
			echo $room_id." ";
			echo $course_id." ";
			foreach($weeks as $week_id)
			{
				$string = "INSERT INTO occupied(Course_ID, Room_ID, Semester_ID,M, T, W, R, F, Week_ID, occupied_ID)
				VALUES('$course_id', '$room_id', '$semester_id', '".$days['M']."', '".$days['T']."', '".$days['W']."', '".$days['R']."', '".$days['F']."', '$week_id', '$occupied_id')";
				$query = mysqli_query($this->con, $string);
			}
		}
		// function to get a unique collision id which is not already in the table
		private function getUniqueCollID()
		{
			$string = "SELECT FLOOR(RAND() * 99999) AS Coll_ID 
			FROM collision 
			WHERE 'Coll_ID' NOT IN (SELECT Coll_ID FROM collision) LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			$string = "SELECT Coll_ID FROM collision";
			$query = mysqli_query($this->con, $string);
			$collision_id = mysqli_fetch_assoc($query);
			if(empty($collision_id))
			{
				return 1;
			}
			return $row['Coll_ID'];
		}
        // method to get a unique occupied id which is not already in the table
		private function getUniqueOccupiedID()
		{
			$string = "SELECT MAX(occupied_ID) as occupied_id FROM occupied";
			$query = mysqli_query($this->con, $string);
			$row = mysqli_fetch_assoc($query);
			if(empty($row))
			{
				return 1;
			}
			return $row['occupied_id']+1;
		}
		// function to cancle registration for a course
		public function cancelRegistration($course_id, $room_id)
		{
			$string = "DELETE FROM occupied WHERE Course_ID = '$course_id' AND Room_ID='$room_id'";
			mysqli_query($this->con, $string);
		}
        public function getRoomName($room_id)
		{
			$string = "SELECT Name FROM room WHERE ID = '$room_id' LIMIT 1";
			$query = mysqli_query($this->con,$string);
			$row = mysqli_fetch_assoc($query);
			return $row['Name'];
		}
	}
?>