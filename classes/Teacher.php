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
			while($row = mysqli_fetch_assoc($check_database_query))
			{
				$rows[] = $row['Course_ID']. " ". $row['Prefix']." ". $row['Number']. " ". $row['Title'];
			}
			//$row = mysqli_fetch_assoc($check_database_query);
			//print_r($rows);
			return $rows;

		}
		public function createEvent()
		{
			
		}
	}
?>