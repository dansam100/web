<?php
	namespace Rexume\Controllers;
	
	use Rexume\Controllers;
	
	require_once("Controller.php");
	
	class LoginController extends \Rexume\Controllers\Controller
	{	
		public function __construct($model, $view, $action)
		{
			parent::__construct($model, $view, $action);
		}
			
		public function load($queryString = null)
		{
			$this->model->setEmail($queryString);
			print_r($queryString);
			if(isset($_SESSION['userId']) && $authentication->validateSession())
			{
				//redirect to home screen
				header("location: home");
			}	
		}
		
		public function doLogin()
		{
			global $AUTH_SUCCESS;
			
			//read the set email and password
			$this->getModel()->setEmail($_POST['email']);
			$this->getModel()->setPassword($_POST['password']);
			
			//authenticate the user
			//initialize the auth object for performing login
			$authentication = new \Rexume\Models\Auth\Authentication();
			$authSuccess = $authentication->doLogin($this->getModel());
			if($AUTH_SUCCESS->SUCCESS == $authSuccess)
			{
				//redirect to home screen
				header("location: home");
			}
			else {
				//invalid login. if user is not accessing default site login page, redirect to it
				header("location: login");
			}
		}
		
		public function linkedin()
		{
			
		}
		
		public function getError()
		{
			return "TESTING ERROR MESSAGE";
		}
	}
?>