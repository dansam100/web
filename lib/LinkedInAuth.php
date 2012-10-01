<?php
namespace Rexume\Models\Auth;
use Rexume\Readers;

class LinkedInAuth extends Authentication
{
    private $oauthObject;
    use \Rexume\Readers\OAuthBase;

    public function __construct()
    {
        parent::__construct();
        $this->name = "LinkedIn";
        $this->oauthObject = new \OAuthSimple();
    }

    /**
        * Gets the oAuth access token for the requesting user
        * @return LoginModel containing the login model
        */	
    public function getAuthentication()
    {
        // In step 3, a verifier will be submitted.  If it's not there, we must be
        // just starting out. Let's do step 1 then.
        try{
            if (!isset($_GET['oauth_verifier']))
            {
                // Step 1: Get a Request Token
                $request_signature = $this->constructRequestSignature();
                $result = $this->oauthObject->sign($request_signature);
                // The above object generates a simple URL that includes a signature, the 
                // needed parameters, and the web page that will handle our request.  I now
                // "load" that web page into a string variable.
                $page = getWebContent($result['signed_url']);
                // We parse the string for the request token and the matching token secret
                $returned_items = array();
                parse_str($page, $returned_items);
                if(isset($returned_items['oauth_token']) && isset($returned_items['oauth_token_secret']))
                {
                    //get the request token and secret
                    $request_token = $returned_items['oauth_token'];
                    $request_token_secret = $returned_items['oauth_token_secret'];

                    // We will need the request token and secret after the authorization.
                    // Set a cookie, so the secret will be available once we return to this page.
                    setcookie("oauth_token_secret", $request_token_secret, time()+3600);
                    //////////////////////////////////////////////////////////////////////////
                    // Step 2: Authorize the Request Token
                    $auth_signature = $this->constructAuthorizationSignature($request_token);
                    $result = $this->oauthObject->sign($auth_signature);
                    // See you in a sec in step 3.
                    header("location: $result[signed_url]");
                }
                else{
                    throw new AuthenticationException("Unable to get request token with LinkedIn oAuth request", AuthenticationStatus::get()->ERROR, null);
                }
                exit;
            }
            else {			    
                // Build the request-URL sending the secret and token received in the get request
                $access_signature = $this->constructAccessSignature($_GET['oauth_token'], $_COOKIE['oauth_token_secret'], $_GET['oauth_verifier']);
                $result = $this->oauthObject->sign($access_signature);	
                // ... and grab the resulting string again. 
                $page = getWebContent($result['signed_url']);

                // Voila, we've got a long-term access token.
                parse_str($page, $returned_items);
                if(isset($returned_items['oauth_token']) && isset($returned_items['oauth_token_secret']))
                {
                    $access_token = $returned_items['oauth_token'];
                    $access_token_secret = $returned_items['oauth_token_secret'];
                    //return a login model containing the oAuth token and secret as the authentication object
                    return new \Rexume\Models\LoginModel(null, null, $access_token, $access_token_secret);
                }
                else{
                    throw new AuthenticationException("Unable to get access token with LinkedIn oAuth request", AuthenticationStatus::get()->ERROR);
                }
            }
        }
        catch(\Rexume\Models\Auth\AuthenticationException $ae){
            throw $ae;
        }
        catch(Exception $e){
            throw new AuthenticationException("Unknown error encountered during authentication", AuthenticationStatus::get()->ERROR, $e);
        }
    }

    /**
     * Called to authenticate the user using linked in tokens acquired in the previous step
     * @param string $accessToken
     * @param string $accessTokenSecret
     * @return string The success code, whether login is successful or not
     * @throws \Rexume\Models\Auth\AuthenticationException when authentication fails. this exception encapsulates the actual exception thrown
     */
    public function authenticate($accessToken, $accessTokenSecret)
    {
        try
        {
            //get data request protocol for linkedin
            $protocol = \Rexume\Configuration\Configuration::getInstance()->getAuthenticationProtocol($this->name);
            //construct url
            $url = $protocol->scope();
            $query = $protocol->query();
            //perform the read operation
            $reader = new \Rexume\Readers\OAuthReader($this->name);
            $page = $reader->read($url, $query, $accessToken, $accessTokenSecret);
            $user = $protocol->parseOne($page);
            if(isset($user))
            {
                $member_id = $user->memberId(); //for LinkedIn, we want the member id
                if(isset($member_id)){
                    //see if we already have a user like this
                    $user = \DB::getOne('User', array('memberId' => $member_id));
                    if(empty($user)){
                        $password = null; $email = null;
                        //generate a fake username for the new user
                        $username = $this->generateString(strlen($member_id));
                        //create a user using the member id and authenticate
                        $user = $this->createUser($username, $password, $user->firstName(), $user->lastName(), $email, $member_id, $accessToken, $accessTokenSecret, $isVerified = 0);
                    }
                    if(isset($user)){
                        $user->oauthToken($accessToken);
                        $user->oauthSecret($accessTokenSecret);
                        \DB::save($user);
                        return $this->loginUser($user);
                    }
                    else{
                        throw new AuthenticationException("Unable to create or find user", AuthenticationStatus::get()->ERROR, $e);
                    }
                }
            }
        }
        catch(AuthenticationException $ae){
            throw $ae;
        }
        catch(Exception $e)
        {
            throw new AuthenticationException("Unknown error encountered during authentication", AuthenticationStatus::get()->ERROR, $e);
        }
    }
}
