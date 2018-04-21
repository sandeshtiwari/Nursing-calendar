<?php
	class Student
	{
		private $con;
		private $email;
		public function __construct($con, $email)
		{
			$this->con = $con;
			$this->email = $email;
		}
		public function getJSON()
		{
			$data = array();
			$databaseQuery = "SELECT DISTINCT occupied_ID, occupied.Course_ID as id, Number, Title as title
			FROM course, person, occupied, registered 
			WHERE registered.Course_ID = course.Course_ID AND person.CWID = registered.CWID AND occupied.Course_ID = course.Course_ID AND person.email = '$this->email'";
			$result = mysqli_query($this->con, $databaseQuery);
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
				$row['title'] .= $this->getRoomNames($Course_ID, $occupied_ID);
				$row['title'] = $Course_ID."\n".$row['title'];
				$row['dow'] = $dow;
				$row['dowstart'] = date('Y-m-d', strtotime($dateRow['start_date']));
				$row['dowend'] = date('Y-m-d', strtotime($dateRow['end_date']));
				$row['start'] = $days['Start_time'];
				$row['end'] = $days['End_time'];
				$data[] = $row;
			}
			return json_encode($data);
		}
		private function getRoomNames($Course_ID,$occupied_ID)
		{
			//echo "here";
			$roomString = "SELECT room.Name as roomName FROM room, occupied WHERE room.ID = occupied.Room_ID AND Course_ID = '$Course_ID' AND occupied_ID = '$occupied_ID' LIMIT 1";
			$roomQuery = mysqli_query($this->con, $roomString);
			//$rooms = "";
			$room = mysqli_fetch_assoc($roomQuery);
			//echo $room['roomName'];
			return "\n".$room['roomName'];
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

		/************************************************************
		Methods for the notes
		*******************************************************/
		
		/***********************************************************/
		public function getMyClasses($id){

			$classes = array();
			$sql = "SELECT Course_ID FROM `registered` WHERE CWID = $id";
			$res = mysqli_query($this->con, $sql);

				while($class = mysqli_fetch_assoc($res)){

					$oneClass = array();
					$oneClass['Course_ID'] = $class['Course_ID'];
					$classes[] = $oneClass;
				}

			return $classes;
		}



		public function getID($email)
		{

			$sql = "SELECT DISTINCT CWID from person where email = '$email';";
			$res = mysqli_query($this->con, $sql);

			if ($id = mysqli_fetch_assoc($res)){
				$CWID = $id['CWID'];
				return $CWID;
			}
			else{
				echo "Mistake";
			}
		}

		public function getCourseName($crn){
			$sql = "SELECT Title FROM `course` WHERE Course_ID = $crn";
			$res = mysqli_query($this->con, $sql);
			if ($title = mysqli_fetch_assoc($res)){
				$Title = $title['Title'];
				return $Title;
			}	
			else{
				echo "Mistake";
			}
		}

		public function weekID(){

			$today =  date('Y-m-d');

			$weeks = $this->weeks();

			$thisWeekId = 0;

			 if(!empty($weeks)){

        		foreach($weeks as $week => $details ){

		    		$start_date = $details['start_date'];
					$end_date = $details['end_date'];
					$ID = $details['ID'];

					
					//We checking if the date is bigger than start date, because the last week that access this loop will has right ID.
					if( $today >= $start_date ) {						

						$thisWeekId = $ID;

					}				


        		}
        	}

        	return $thisWeekId;		

		}

		 public function getLatestSem()
		{
			$string = "SELECT ID, MAX(end_date) FROM semester LIMIT 1";
			$query = mysqli_query($this->con, $string);
			$sem = mysqli_fetch_assoc($query);
			return $sem['ID'];
		}

		public function weeks(){
			
					
			$weeks = array();	
			$semester = $this->getLatestSem();
			$sql = "SELECT ID, start_date, end_date FROM week WHERE semester_ID = $semester";
			$res = mysqli_query($this->con, $sql);

				while($week = mysqli_fetch_assoc($res)){

					$oneWeek = array();

					$oneWeek['ID'] = $week['ID'];
					$oneWeek['start_date'] = $week['start_date'];
					$oneWeek['end_date'] = $week['end_date'];
					

					$weeks[] = $oneWeek;

				}
			return $weeks;
		}

		public function selectNotes($crn, $weekID){
			$notes = array();	
			$semester = $this->getLatestSem();
			$sql = "SELECT Note, Name FROM notes WHERE Week_ID = $weekID and Semester_ID = $semester and Course_ID = $crn";
			$res = mysqli_query($this->con, $sql);

				while($note = mysqli_fetch_assoc($res)){

					$oneNote = array();

					$oneNote['Note'] = $note['Note'];
					$oneNote['Name'] = $note['Name'];					

					$notes[] = $oneNote;

				}
			return $notes;


		}



	}
?>