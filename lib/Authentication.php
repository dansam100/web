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
		
		public function __construct($siteKey = null)
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
		
		public function createUser($email, $password, $oAuthToken, $isAdmin = 0, $isVerified = 0, $isActive = 1)
		{
			if((isset($password)))
			{
				$user_salt = $this->generateSalt();
				$password = $this->hashData($user_salt . $password);
				$verification_code = $this->generateSalt();
				
				//TODO: wrap create user in something cleaner
				//create user here
				$user = new \User();
				$user->setPassword($password);
				$user->setEmail($email);
				$user->setOAuthToken($oAuthToken);
				$user->isAdmin($isAdmin);
				$user->isVerified($isVerified);
				$user->isActive($isActive);
				
				//TODO: call verification process
				
				return $user;
			}
		}
		
		
		public function login($email, $password, $oauthId = null)
		{
			global $entityManager;
			
			//find user based on email address
			$user = $entityManager->getRepository('User')->findOneBy(array('email' => $email));
			
			//create salt for new user
			if($user)
			{
				$user_salt = $user->getSalt();
				$user_oauth_token = $user->getOAuthId();
				$password = $this->hashData($password . $user_salt);
				
				if($user && ($user->getPassword() == $password || $user->getOAuthId() == $oauthId))
				{
					//verification and active checks
					if($user->isActive())
					{
						if($user->isVerified())
						{
							//create session
							$token = $this->hashData($this->generateSalt() . $_SESSION["HTTP_USER_AGENT"]);
							
							//TODO: clear old session value for the user
							$currentSessions = $entityManager->getRepository('Session')->findBy(array('userId' => $user->id));
							$entityManager->remove($currentSessions);
							
							//re-initialize and save session tokens
							$_SESSION["token"] = $token;
							$_SESSION["userId"] = $user->getId();
							
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
			
			//read the user
			$user = $entityManager->find('User', $_SESSION["userId"]);
			
			if($user)
			{
				//Validate session against user's session id and session token values
				if($user->getSessionId() == session_id() && $_SESSION["token"] == $user->getToken())
				{
					$this->invalidateSession();
					return true;
				}
			}
			
			//return:
			return false;
		}
		
		
		public function invalidateSession()
		{
			//regenerate the session id
			session_regenerate_id();
			
			//create a new token for the user
			$token = $this->hashData($this->generateSalt() . $_SESSION["HTTP_USER_AGENT"]);
			
			//replace the session token
			$_SESSION["token"] = $token;
		}
   	}