<?php
	namespace Rexume\Models;
	
	require_once("Model.php");
	class LoginModel extends Model
	{
		private $email;
		private $password;
		
		public function setEmail($email)
		{
			$this->email = $email;
		}
		
		public function getEmail()
		{
			return $this->email;
		}
		
		public function setPassword($password)
		{
			$this->email = $password;
		}
		
		public function getPassword()
		{
			return $this->password;
		}
	}
