<?php
	require_once("Controller.php");
	
	use Rexume\Controllers;
	
	class LoginController extends \Rexume\Controllers\Controller
	{	
		public function load($queryString)
		{
			
			$model->setEmail($queryString);
		}
		
		public function doLogin()
		{
			//read the set email and password
			$this->getModel()->setEmail($_POST['email']);
			$this->getModel()->setPassword($_POST['password']);
			
			//authenticate the user
			//TODO: write authentication code
			$authentication = new \Rexume\Models\Auth\Authentication();
			
		}
		
		public function getError()
		{
			return "TESTING ERROR MESSAGE";
		}
	}
?>