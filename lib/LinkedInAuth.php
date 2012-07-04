<?php
namespace Rexume\Models\Auth;
require_once(LIBRARIES_FOLDER . DS . "Authentication.php");
require_once(LIBRARIES_FOLDER . DS . "oauth_simple". DS . "php" . DS . "OAuthSimple.php");

class LinkedInAuth extends Authentication
{
    private $name;
    private $oauthObject;

    public function __construct()
    {
        parent::__construct();
        $this->name = "LinkedIn";
        $this->oauthObject = new \OAuthSimple();
    }

    /**
        * Gets the oAuth access token for the requesting user
        * @return LoginModel
        */	
    public function getAuthentication()
    {
        // In step 3, a verifier will be submitted.  If it's not there, we must be
        // just starting out. Let's do step 1 then.
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
                return AuthenticationStatus::get()->ERROR;
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
                return AuthenticationStatus::get()->ERROR;
            }
        }
    }


    public function authenticate($accessToken, $accessTokenSecret)
    {
        //recreate request with new signatures
        $this->oauthObject->reset();
        //get the default signatures //append access token signatures
        $signatures = $this->getSignatures();
        $signatures['oauth_token'] = $accessToken;
        $signatures['oauth_secret'] = $accessTokenSecret;
        
        //get data request protocol for linkedin
        $protocol = \Rexume\Configuration\Configuration::getInstance()->getAuthenticationProtocol($this->name);

        $result = $this->oauthObject->sign(array('path' => $protocol->getScope() . $protocol->getQuery(), 'signatures'=> $signatures));
        $page = \getWebContent($result['signed_url']);
        //TODO: get email address and/or member id
        $email = null; $password = null;
        //get the required id from the xml content
        $data = $protocol->parseOne($page);
        if(isset($data)){
            //for LinkedIn, we want the member id
            $member_id = $data->memberId();
            if(isset($member_id)){
                //create a user using the member id and authenticate
                $user = parent::createUser($email, $password, $member_id, $accessToken, $accessTokenSecret, $isVerified = 1);
                if($user){
                    return parent::login($email, $password, $member_id);
                }
            }
        }
        return AuthenticationStatus::get()->INVALID_LOGIN;
    }

    /**
     * Gets the default signatures required to make an oAuth acessToken call
     * @return array An array of signed keys
     */
    protected function constructAccessSignature($oauthToken, $oauthSecret, $oauthVerifier)
    {
        $auth_key = \Rexume\Configuration\Configuration::getInstance()->getAuthorizationKey($this->name);
        $signatures = $this->getSignatures();

        // Fetch the cookie and amend our signature array with the request
        // token and secret.
        $signatures['oauth_secret'] = $oauthSecret;
        $signatures['oauth_token'] = $oauthToken;

        return array(
            'path'      => $auth_key->getAccessTokenUrl(),
            'parameters'=> array(
                'oauth_token' => $oauthToken,
                'oauth_verifier' => $oauthVerifier),
            'signatures'=> $signatures
        );
    }

    /**
     * Constructs the default signatures required to make an oAuth authoriseToken call
     * @return array An array of signed keys
     */
    protected function constructAuthorizationSignature($oauthToken)
    {
        $auth_key = \Rexume\Configuration\Configuration::getInstance()->getAuthorizationKey($this->name);
        $signatures = $this->getSignatures();

        return array(
            'path'       => $auth_key->getAuthorizeTokenUrl(),
            'parameters' => array('oauth_token' => $oauthToken),
            'signatures' => $signatures
        );
    }
    
    /**
     * Constructs the default signatures required to make an oAuth requestToken call
     * @return array An array of signed keys
     */
    protected function constructRequestSignature()
    {			
        $auth_key = \Rexume\Configuration\Configuration::getInstance()->getAuthorizationKey($this->name);
        $signatures = $this->getSignatures();
        return array(
            'path' => $auth_key->getRequestTokenUrl(),
            'parameters' => array('oauth_callback' => $auth_key->getCallback()),
            'signatures'=> $signatures
        );
    }

    /**
     * Gets the default signatures required to make an oAuth call
     * @return array An array of signed keys
     */
    protected function getSignatures()
    {
        $auth_key = \Rexume\Configuration\Configuration::getInstance()->getAuthorizationKey($this->name);
        return array(
            'consumer_key' => $auth_key->getApiKey(), 
            'shared_secret' => $auth_key->getSharedSecret()
        );
    }
}
