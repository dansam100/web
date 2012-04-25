<?php
	namespace Rexume\Models\Auth;
	
	use Rexume\Models\Enums as Enum;
	
    class AuthenticationException extends Exception{}
	
	//TODO: check if we need to qualify path for namespace here
	$AUTH_STATUS = new Enum\FlagsEnum("SUCCESS", "NOT_VERIFIED", "INACTIVE", "INVALID_LOGIN", "ERROR");
    
    class Authentication
   	{
   		private $siteKey;
		
		public function __construct()
		{
			//TODO: get site key from configuration?	
			$this->siteKey = "REXUMESITETEST";
		}
		
		
		private function generateSalt($length = 32)
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
			//TODO: implement check for admin	
			return false;
		}
		
		public function isVerified()
		{
			//TODO: implement check for verifiedness	
			return false;
		}
		
		public function isActive()
		{
			//TODO: implement check for activeness	
			return false;
		}
		
		public function createUser($email, $password, $oAuthToken, $isAdmin = 0, $isVerified = 0, $isActive = 1)
		{
			if((isset($password)))
			{
				$user_salt = $this->generateSalt();
				$password = $this->hashData($user_salt . $password);
				$verification_code = $this->generateSalt();
				
				//TODO: create user here
				$user = NULL;
				
				//TODO: call verification process
				
				return $user;
			}
		}
		
		
		public function login($email, $password)
		{
			//TODO: find user based on email address
			$user = NULL;
			
			//TODO: proper implementation
			$user_salt = $user->getSalt();
			$password = $this->hashData($password . $user_salt);
			
			if($user && $user->getPassword() == $password)
			{
				//TODO: fix verification and active checks
				if($user->isActive())
				{
					if($user->isVerified())
					{
						//create session
						$token = $this->hashData($this->generateSalt() . $_SESSION["HTTP_USER_AGENT"]);
						
						//TODO: clear old session value for the user
						
						
						//re-initialize and save session tokens
						$_SESSION["token"] = $token;
						$_SESSION["userId"] = $user->getUniqueId();
						
						//TODO: insert new session values for user
						$sessionCreated = NULL;
						
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

		
		public function validateSession()
		{
			//TODO: read the user
			$user = NULL;
			
			if($user)
			{
				//TODO: fix checks to 'getSessionId()' and 'getToken'
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
?>