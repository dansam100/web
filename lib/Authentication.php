<?php
namespace Rexume\Models\Auth;
require_once(LIBRARIES_FOLDER . DS . "Enum.php");
require_once(LIBRARIES_FOLDER . DS . "Bootstrap.php");

class AuthenticationException extends \Exception{}

/**
* Authentication state flags
*/
class AuthenticationStatus
{
    private static $auth_status;
    private $statuses;
    public function __construct() {
        $this->statuses = new \Rexume\Models\Enums\FlagsEnum("SUCCESS", "NOT_VERIFIED", "INACTIVE", "INVALID_LOGIN", "ERROR");;
    }
    
    public function __get(/*string*/ $name)
    {
        return $this->statuses->$name;
    }
    
    public static function get()
    {
        if(isset(self::$auth_status))
        {
            return self::$auth_status;
        }
        else return self::$auth_status = new AuthenticationStatus();
    }
}


/**
 * Base authentication class
 */
class Authentication
{
    private $siteKey;
    
    public function __construct()
    {
        $this->siteKey = \Rexume\Configuration\Configuration::getInstance()->getSiteKey();
    }

    /**
     * Generates a random IV used for encryption
     * @param type $length The length of IV to create
     * @return string
     */
    protected function generateSalt($length = 32)
    {
        $salt = bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        return $salt;
    }
    
    /**
     * a string containing the calculated message digest as lowercase hexits unless raw_output is set to true in which case the raw binary representation of the message digest is returned.
     * @param type $data Any string to hash
     * @return string
     */
    protected function hashData($data)
    {
        return hash_hmac('sha512', $data, $this->siteKey);
    }

    public function isAdmin()
    {
        $entityManager = \DB::getInstance();
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
       $entityManager = \DB::getInstance();

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
        $entityManager = \DB::getInstance();

        //GetUser and check for verified-ness
        $user = $entityManager->find('User', $_SESSION["userId"]);
        if(isset($user))
        {
            return $user->isActive();
        }
        return false;
    }

    public function createUser($email, $password, $memberId = null, $oauthToken = null, $oauthSecret = null, $isVerified = 0, $isActive = 1, $isAdmin = 0)
    {
        $verification_code = $this->generateSalt();
        if(!isset($memberId))
        {
            $memberId = $this->generateSalt(10);
        }
        $user = new \User();
        if(isset($email) && isset($password))
        {
            $user_salt = $this->generateSalt();
            $password = $this->hashData($user_salt . $password);
            $user->password($password);
            $user->email($email);
        }        
        if(isset($oauthSecret) && isset($oauthToken))
        {
            //TODO: wrap create user in something cleaner
            $user->oauthToken($oauthToken);
            $user->oauthSecret($oauthSecret);
        }
        $user->isAdmin($isAdmin);
        $user->isVerified($isVerified);
        $user->isActive($isActive);
        $user->memberId($memberId);
        $user->verificationCode($verification_code);
        
        //TODO: call verification process
        
        //Save user
        \DB::getInstance()->save($user);
        
        //return:
        return $user;
    }

    /**
     * Logs a given user in using the $email and password or a member id
     * @param type $email The email to login as
     * @param type $password The authentication password to use
     * @param type $memberId An alternative to the password for oAuth authentications
     * @return FlagsEnum the success code of the login
     */
    public function login($email, $password, $memberId = "")
    {
        $entityManager = \DB::getInstance();

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
                            AuthenticationStatus::get()->SUCCESS;
                        }
                        else return AuthenticationStatus::get()->ERROR;
                    }
                    else return AuthenticationStatus::get()->NOT_VERIFIED;
                }
                else return AuthenticationStatus::get()->INACTIVE;
            }
            else return AuthenticationStatus::get()->INVALID_LOGIN;
        }
        else return AuthenticationStatus::get()->ERROR;
    }

    public function validateSession()
    {
        global $entityManager;
        if(isset($_SESSION['token']) && isset($_SESSION['userId']))
        {
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
            $_SESSION['token'] = $token;    //replace the session token
        }
        //return:
        return true;
    }
}