<?php
	namespace Rexume\Controllers;
	
	use Rexume\Controllers;
	
	require_once("Controller.php");
	
	class LoginController extends \Rexume\Controllers\Controller
	{	
		public function __construct($model, $view, $action)
		{
			parent::__construct($model, $view, $action);
			
			//read the set email and password
			$this->model->setEmail($_POST['email']);
			$this->model->setPassword($_POST['password']);
		}
			
		public function simple($queryString = null)
		{
			$this->model->setEmail($queryString);
			print_r($queryString);
			if(isset($_SESSION['userId']) && $authentication->validateSession())
			{
				header("location: home"); //redirect to home screen
			}	
		}
		
		
		public function doLogin($oauth = false)
		{
			global $AUTH_SUCCESS;
			//only login if the user is not already logged in
			if(isset($_SESSION['userId']) && $authentication->validateSession()){		
				//authenticate the user using Auth object
				$email = null; $password = null;	//initialize vars
				$authentication = new \Rexume\Models\Auth\Authentication();
				if($oauth)
				{
					$token = $this->model->getOAuthToken();
					$secret = $this->model->getOAuthSecret();
					$authSuccess = $authentication->login($email, $password, $token, $secret);
				}
				else{
					$email = $this->model->getEmail();
					$password = $this->model->getPassword();
					$authSuccess = $authentication->login($email, $password);
				}
				if($AUTH_SUCCESS->SUCCESS == $authSuccess){
					header("location: home"); //redirect to home screen
				}
				else {
					header("location: login"); //invalid login. if user is not accessing default site login page, redirect to it
				}
			}
		}
		
		public function linkedin($queryString = null)
		{
			global $AUTH_SUCCESS;
			//authenticate the user using the linkedin oAuth object
			$authentication = new \Rexume\Models\Auth\LinkedInAuth();
			$loginModel = $authentication->getAuthentication();
			
			//use the returned login model to authenticate the user
			if(isset($loginModel))
			{
				$this->model = $loginModel;
				$this->doLogin($oauth = true);
			}
		}
		
		public function getError()
		{
			return "TESTING ERROR MESSAGE";
		}
	}
?>