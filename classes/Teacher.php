<?php
	class Teacher
	{
		private $con;
		private $username;
		public function __construct($con, $username)
		{
			$this->con = $con;
			$this->username = $_SESSION['username'];
		}
		public function displayMyClasses()
		{

		}
		public function createEvent()
		{
			
		}
	}
?>