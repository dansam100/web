<?php
namespace Rexume\Models\Auth;
require_once("Authentication.php");
require_once("oauth_simple". DS . "php" . DS . "OAuthSimple.php");

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
                print_r($auth_signature);

                // See you in a sec in step 3.
                header("location: $result[signed_url]");
            }
            else echo "Invalid returned values";
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

                // Complete login process by authenticating the user
                return new \Rexume\Models\LoginModel(null, null, $access_token, $access_token_secret);
            }
            else echo "FAIL";
        }
        return null;
    }


    public function authenticate($accessToken, $accessTokenSecret)
    {
        $auth_key = getConfiguration()->getAuthorizationKey($this->name);

        //Save the user if this is the first login
        //get the default signatures
        $signatures = $this->getSignatures();
        //append access token signatures
        $signatures['oauth_token'] = $accessToken;
        $signatures['oauth_secret'] = $accessTokenSecret;

        //recreate request with new signatures
        $this->oauthObject->reset();
        $result = $this->oauthObject->sign(array('path' => $auth_key->getScope(), 'signatures'=> $signatures));
        $page = getWebContent($result['signed_url']);



        //TODO: get email address and/or member id
        $email = null;
        $member_id = null;
        parent::login($email, null, $member_id);
    }


    protected function constructAccessSignature($oauthToken, $oauthSecret, $oauthVerifier)
    {
        $authKey = getConfiguration()->getAuthorizationKey($this->name);
        $signatures = $this->getSignatures();

        // Fetch the cookie and amend our signature array with the request
        // token and secret.
        $signatures['oauth_secret'] = $oauthSecret;
        $signatures['oauth_token'] = $oauthToken;

        return array(
            'path'      => $authKey->getAccessTokenUrl(),
            'parameters'=> array(
                'oauth_token' => $oauthToken,
                'oauth_verifier' => $oauthVerifier),
            'signatures'=> $signatures
        );
    }

    protected function constructAuthorizationSignature($oauthToken)
    {
        $auth_key = getConfiguration()->getAuthorizationKey($this->name);
        $signatures = $this->getSignatures();

        return array(
            'path'       => $auth_key->getAuthorizeTokenUrl(),
            'parameters' => array('oauth_token' => $oauthToken),
            'signatures' => $signatures
        );
    }

    protected function constructRequestSignature()
    {			
        $auth_key = getConfiguration()->getAuthorizationKey($this->name);
        $signatures = $this->getSignatures();

        return array(
            'path' => $auth_key->getRequestTokenUrl(),
            'parameters' => array('oauth_callback' => $auth_key->getCallback()),
            'signatures'=> $signatures
        );
    }

    protected function getSignatures()
    {
        $appConfig = getConfiguration();
        $auth_key = $appConfig->getAuthorizationKey($this->name);
        return array(
            'consumer_key' => $auth_key->getApiKey(), 
            'shared_secret' => $auth_key->getSharedSecret()
        );
    }
}
