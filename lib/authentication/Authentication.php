<?php
namespace Rexume\Lib\Authentication;
use \Rexume\Application\Models\Enums;

/**
 * Exception thrown during authentication
 */
class AuthenticationException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
* Authentication state flags
*/
class AuthenticationStatus
{
    private static $auth_status;
    private $statuses;
    public function __construct() {
        $this->statuses = new Enums\FlagsEnum("SUCCESS", "NOT_VERIFIED", "INACTIVE", "INVALID_LOGIN", "ERROR");
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
    protected $name;
    
    public function __construct()
    {
        $this->name = 'basic';
        $this->siteKey = \Rexume\Config\Configuration::getInstance()->getSiteKey();
    }
    
    public function name()
    {
        return $this->name;
    }

    /**
     * Generates a random IV used for encryption
     * @param int $length The length of IV to create
     * @return string
     */
    protected function generateSalt($length = 16)
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
    
    protected function generateString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        $str = '';
        $count = strlen($charset);
        while ($length--)
        {
            $str .= $charset[mt_rand(0, $count-1)];
        }
        return $str;
    }

    public function isAdmin()
    {
        $user = \DB::getOne('User', array(
                'user' => $_SESSION['userId']
            )
        );
        if(isset($user))
        {
            return $user->isAdmin();
        }
        return false;
    }

    public function isVerified()
    {
       $user = \DB::getOne('User', array(
                'user' => $_SESSION['userId']
            )
        );
        if(isset($user))
        {
            return $user->isVerified();
        }
        return false;
    }

    public function isActive()
    {
        $user = \DB::getOne('User', array(
                'user' => $_SESSION['userId']
            )
        );
        if(isset($user))
        {
            return $user->isActive();
        }
        return false;
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $memberId
     * @param string $oauthToken
     * @param string $oauthSecret
     * @param boolean $isVerified
     * @param boolean $isActive
     * @param boolean $isAdmin
     * @return \User the created user
     * @throws Exception on failure to create and persist user
     * @throws AuthenticationException
     */
    public function createUser($username, $password, $firstName, $lastName, $email = null, $memberId = null, $oauthToken = null, $oauthSecret = null, $isVerified = 0, $isActive = 1, $isAdmin = 0)
    {
        $verification_code = $this->generateString(16);
        if(empty($memberId))
        {
            $memberId = $this->generateString(10);
        }
        $user = new \User();
        if(!empty($username))
        {
            $user->userName($username);
        }
        if(!empty($password))
        {
            $user_salt = $this->generateSalt();
            $password = $this->hashData($user_salt . $password);
            $user->password($password);
            $user->userSalt($user_salt);
        }
        if(!empty($oauthSecret) && !empty($oauthToken))
        {
            //TODO: wrap create user in something cleaner
            $user->oauthToken($oauthToken);
            $user->oauthSecret($oauthSecret);
        }
        else{
            throw new Exception("Invalid parameters passed to '" . __FUNCTION__ ."'");
        }
        $user->email($email);
        $user->firstName($firstName);
        $user->lastName($lastName);
        $user->isAdmin($isAdmin);
        $user->isVerified($isVerified);
        $user->isActive($isActive);
        $user->memberId($memberId);
        $user->verificationCode($verification_code);
        
        //TODO: call verification process
        
        //Save user
        try{
            \DB::save($user);
        }
        catch (Exception $e){
            throw new AuthenticationException("Unable to create user", AuthenticationStatus::get()->ERROR, $e);
        }
        
        //return:
        return $user;
    }
    
    /**
     * Log a given user into the application
     * @param \User $user the user to login as
     */
    public function loginUser($user)
    {
        if(!empty($user)){
            return $this->login($user->userName(), $user->password(), $user->memberId());
        }
        return AuthenticationStatus::get()->ERROR;
    }

    /**
     * Logs a given user in using the $email and password or a member id
     * @param string $username The email to login as
     * @param string $password The authentication password to use
     * @param string $memberId An alternative to the password for oAuth authentications
     * @return FlagsEnum the success code of the login
     */
    public function login($username, $password, $memberId = null)
    {
        try
        {
            $entityManager = \DB::getInstance();
            //find user based on email address
            $user = null;
            if(isset($username)){
                $user = $entityManager->getRepository('User')->findOneBy(array('username' => $username));
            }
            else{
                $user = $entityManager->getRepository('User')->findOneBy(array('memberId' => $memberId));
            }
            //create salt for new user
            if(isset($user))
            {
                $user_salt = $user->userSalt();
                $user_password = $this->hashData($password . $user_salt);

                if($user->memberId() == $memberId || $user->password() == $user_password)
                {
                    //verification and active checks
                    if($user->isActive())
                    {
                        //create session
                        $token = $this->hashData($this->generateSalt() . $_SERVER['HTTP_USER_AGENT']);
                        //TODO: support multiple sessions later?
                        //clear old session value for the user
                        $currentSessions = $entityManager->getRepository('Session')->findBy(array('user' => $user));
                        \DB::remove($currentSessions);

                        //TODO: find cleaner way to insert new session values for user
                        //Create the user session object
                        $session = new \Session();
                        $session->user($user);
                        $session->sessionId(session_id());
                        $session->token($token);
                        //save the session
                        \DB::save($session);
                        //re-initialize and save session tokens
                        $_SESSION['token'] = $token;
                        $_SESSION['userId'] = $user->getId();
                        //return:
                        return $user->isVerified() ? AuthenticationStatus::get()->SUCCESS : AuthenticationStatus::get()->NOT_VERIFIED;
                    }
                    else return AuthenticationStatus::get()->INACTIVE;
                }
                else return AuthenticationStatus::get()->INVALID_LOGIN;
            }
            else return AuthenticationStatus::get()->ERROR;
        }
        catch (\Exception $e)
        {
            throw new AuthenticationException("Error during login", AuthenticationStatus::get()->ERROR, $e);
        }
    }

    public function validateSession()
    {
        if(isset($_SESSION['token']) && isset($_SESSION['userId']))
        {
            //read the session object
            $session = \DB::getOne('Session',
                array(
                    'sessionId' => session_id(), 
                    'token' => $_SESSION['token'], 
                    'user' => $_SESSION['userId']
                )
            );
            if(isset($session))
            {
                //Validate session against user's session id and session token values
                return $this->invalidateSession();
            }
        }
        return false;
    }


    public function invalidateSession()
    {
        //read the session object
        $session = \DB::getOne('Session',
            array(
                'sessionId' => session_id(), 
                'token' => $_SESSION['token'], 
                'user' => $_SESSION['userId']
            )
        );
        if(isset($session))
        {
            session_regenerate_id();    //regenerate the session id
            $token = $this->hashData($this->generateSalt() . $_SERVER['HTTP_USER_AGENT']);  //create a new token for the user
            $session->token($token);
            $session->sessionId(session_id());
            \DB::save($session);    //update the token. if the flush fails, the user will have to login again
            $_SESSION['token'] = $token;    //replace the session token
        }
        //return:
        return true;
    }
    
    /**
     * Gets the currently logged in user
     * @return User the currently logged in user
     */
    public static function currentUser()
    {
        if(isset($_SESSION['token']) && isset($_SESSION['userId']))
        {
            //read the user object
            $user = \DB::getOne('User', array(
                    'id' => $_SESSION['userId']
                )
            );
            return $user;
        }
        return null;
    }
}