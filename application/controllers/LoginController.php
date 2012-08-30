<?php
namespace Rexume\Controllers;

require_once("Controller.php");

class LoginController extends Controller
{	
    /**
     * Overridden to set email and password on the model
     * @param LoginModel $model The model behind the login screen
     * @param LoginView $view The login view controls
     * @param string $action the action to invoke on the screen
     */
    public function __construct($model, $view, $action)
    {
        parent::__construct($model, $view, $action);
        //read the set email and password
        if(isset($_POST['email'])){ $this->model->setEmail($_POST['email']); }
        if(isset($_POST['password'])){ $this->model->setPassword($_POST['password']); }
    }

    public function simple()
    {
        $authentication = new \Rexume\Models\Auth\Authentication();
        if($authentication->validateSession())
        {
            header("location: /home"); //redirect to home screen
        }
    }


    public function doLogin()
    {
        $authentication = new \Rexume\Models\Auth\Authentication();			
        //only login if the user is not already logged in
        if(!$authentication->validateSession())
        {		
            try{
                //authenticate the user using Auth object            
                $email = $this->model->getEmail();
                $password = $this->model->getPassword();
                $auth_success = $authentication->login($email, $password);
                if($auth_success == \Rexume\Models\Auth\AuthenticationStatus::get()->SUCCESS)
                {
                    header("location: /rexume/home");
                }
                else if($auth_success == \Rexume\Models\Auth\AuthenticationStatus::get()->NOT_VERIFIED){
                    header("location: /verify?protocol=$authentication->getName()");
                }
                else{
                    $this->error = "Invalid login";
                }
            }
            catch(\Exception $e)
            {
                 $this->error = "Login failed"; //invalid login. if user is not accessing default site login page, redirect to it
                 throw $e;
            }
        }
        else header("location: /rexume/home");
    }

    public function linkedin()
    {
        //authenticate the user using the linkedin oAuth object
        $authentication = new \Rexume\Models\Auth\LinkedInAuth();
        try{
            //only login if the user is not already logged in
            if(!$authentication->validateSession()){    
                $login_model = $authentication->getAuthentication();
                //use the returned login model to authenticate the user
                if(isset($login_model))
                {
                    $this->model = $login_model;
                    $access_token = $this->model->getOAuthToken();
                    $access_secret = $this->model->getOAuthSecret();
                    $auth_success = $authentication->authenticate($access_token, $access_secret);
                    if($auth_success == \Rexume\Models\Auth\AuthenticationStatus::get()->SUCCESS)
                    {
                        //header("location: /rexume/home");
                        header("location: /rexume/verify?protocol=" . $authentication->getName());
                    }
                    else if($auth_success == \Rexume\Models\Auth\AuthenticationStatus::get()->NOT_VERIFIED){
                        header("location: /verify?protocol=" . $authentication->getName());
                    }
                    else{
                        $this->error = "Invalid login";
                    }
                }
            }
            else //header("location: /rexume/home");
                header("location: /rexume/verify?protocol=" . $authentication->getName());
        }
        catch(Exception $e){
            //log the error and continue
            $this->error = "Login failed"; //invalid login. if user is not accessing default site login page, redirect to it
            throw $e;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}