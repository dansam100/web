<?php
namespace Rexume\Models\Auth;
require_once(SITE_ROOT . DS . "lib" . DS . "Enum.php");
require_once(SITE_ROOT . DS . "lib" . DS . "Bootstrap.php");

class AuthenticationException extends \Exception{}

/**
    * Authentication state flags
    */	
$AUTH_STATUS = new \Rexume\Models\Enums\FlagsEnum("SUCCESS", "NOT_VERIFIED", "INACTIVE", "INVALID_LOGIN", "ERROR");

class Authentication
{
    private $siteKey;

    public function __construct()
    {
        global $appConfig;

        $this->siteKey = $appConfig->getSiteKey();
    }


    protected function generateSalt($length = 32)
    {
        $salt = bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        return $salt;
    }

    protected function hashData($data)
    {
        return hash_hmac('sha512', $data, $this->$siteKey);
    }

    public function isAdmin()
    {
        global $entityManager;

        //GetUser and check for admin-ness
        $user = $entityManager->find('User', $_SESSION["userId"]);
        if(isset($user))
        {
            return $user->isAdmin();
        }
        return false;
    }

    public function isVerified()
    {
        global $entityManager;

        //GetUser and check for verified-ness
        $user = $entityManager->find('User', $_SESSION["userId"]);
        if(isset($user))
        {
            return $user->isVerified();
        }
        return false;
    }

    public function isActive()
    {
        global $entityManager;

        //GetUser and check for verified-ness
        $user = $entityManager->find('User', $_SESSION["userId"]);
        if(isset($user))
        {
            return $user->isActive();
        }
        return false;
    }

    public function createUser($email, $password, $oauthToken = null, $oauthSecret = null, $isVerified = 0, $isActive = 1, $isAdmin = 0)
    {
        $verification_code = $this->generateSalt();
        $user = null;
        if(isset($email) && isset($password))
        {
            $user_salt = $this->generateSalt();
            $password = $this->hashData($user_salt . $password);

            //TODO: wrap create user in something cleaner
            //create user here
            $user = new \User();
            $user->password($password);
            $user->email($email);
            //$user->oauthToken($oauthToken);
            //$user->oauthSecret($oauthSecret);
            $user->isAdmin($isAdmin);
            $user->isVerified($isVerified);
            $user->isActive($isActive);
            $user->verificationCode($verification_code);
            //TODO: call verification process

            return $user;
        }
        elseif(isset($oauthSecret) && isset($oauthToken))
        {
            //TODO: wrap create user in something cleaner
            //create user here
            $user = new \User();
            $user->oauthToken($oauthToken);
            $user->oauthSecret($oauthSecret);
            $user->isAdmin($isAdmin);
            $user->isVerified($isVerified);
            $user->isActive($isActive);
            $user->verificationCode($verification_code);
        }
        //return:
        return $user;
    }


    public function login($email, $password, $memberId = "")
    {
        global $entityManager;
        global $AUTH_STATUS;

        //find user based on email address
        $user = null;
        if(isset($email)){
            $entityManager->getRepository('User')->findOneBy(array('email' => $email));
        }
        else{
            $entityManager->getRepository('User')->findOneBy(array('memberId' => $memberId));
        }
        //create salt for new user
        if($user)
        {
            $user_salt = $user->getSalt();
            $user_member_id = $user->getMemberId();
            $password = $this->hashData($password . $user_salt);

            if($user->getPassword() == $password || $user_member_id == $memberId)
            {
                //verification and active checks
                if($user->isActive())
                {
                    if($user->isVerified())
                    {
                        //create session
                        $token = $this->hashData($this->generateSalt() . $_SESSION['HTTP_USER_AGENT']);

                        //TODO: clear old session value for the user
                        $currentSessions = $entityManager->getRepository('Session')->findBy(array('userId' => $user->id));
                        $entityManager->remove($currentSessions);

                        //TODO: find cleaner way to insert new session values for user
                        //Create the user session object
                        $session = new \Session();
                        $session->setUser($user);
                        $session->setSessionId(session_id());
                        $session->setToken($token);
                        $entityManager->persist($session);
                        $sessionCreated = $entityManager->flush();

                        //return success/failure
                        if($sessionCreated)
                        {
                            //re-initialize and save session tokens
                            $_SESSION['token'] = $token;
                            $_SESSION['userId'] = $user->getId();
                            //return:
                            $AUTH_STATUS->SUCCESS;
                        }
                        else return $AUTH_STATUS->ERROR;
                    }
                    else return $AUTH_STATUS->NOT_VERIFIED;
                }
                else return $AUTH_STATUS->INACTIVE;
            }
            else return $AUTH_STATUS->INVALID_LOGIN;
        }
        else return $AUTH_STATUS->ERROR;
    }

    public function validateSession()
    {
        global $entityManager;
        //read the session object
        $session = $entityManager->getRepository('Session')->findOneBy(
            array(
                'userId' => session_id(), 
                'token' => $_SESSION['token'], 
                'userId' => $_SESSION['userId']
            )
        );
        if($session)
        {
            //Validate session against user's session id and session token values
            return $this->invalidateSession();
        }
        return false;
    }


    public function invalidateSession()
    {
        global $entityManager;
        //regenerate the session id
        session_regenerate_id();
        
        //read the session object
        $session = $entityManager->getRepository('Session')->findOneBy(
            array(
                'userId' => session_id(), 
                'token' => $_SESSION['token'], 
                'userId' => $_SESSION['userId']
            )
        );
        //create a new token for the user
        $token = $this->hashData($this->generateSalt() . $_SESSION['HTTP_USER_AGENT']);
        $session->setToken($token);
        $entityManager->persist($session);
        $sessionCreated = $entityManager->flush();
        //update the token. if the flush fails, the user will have to login again
        if($sessionCreated)
        {
            //replace the session token
            $_SESSION['token'] = $token;
        }
        //return:
        return true;
    }
}