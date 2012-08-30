<?php
namespace Rexume\Models;

require_once("Model.php");

/**
    * LoginModel.php
    * Model behind a login screen
    */
class LoginModel extends Model
{
    private $email;
    private $password;
    private $secret;
    private $token;

    public function __construct($email = null, $password = null, $oauthToken = null, $oauthSecret = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->token = $oauthToken;
        $this->secret = $oauthSecret;
    }

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
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getOAuthSecret()
    {
        return $this->secret;
    }

    public function setOAuthSecret($secret)
    {
        $this->secret = $secret;
    }

    public function getOAuthToken()
    {
        return $this->token;
    }

    public function setOAuthToken($token)
    {
        $this->token = $token;
    }
}
