<?php
namespace Rexume\Readers;
/**
 * Description of oAuthReader
 *
 * @author sam.jr
 */
class OAuthReader {
    private $name;
    private $oauthObject;
    
    use OAuthBase;
    
    /**
     * Constructor
     * @param string  $name The name of the reader. Used to retrieve protocols when necessary
     */
    public function __construct($name) {
        $this->name = $name;
        $this->oauthObject = new \OAuthSimple();
    }
    
    /**
     * A read function for retrieving content using OAuth calls
     * @param string $scope the scope URL to retrieve the content from
     * @param string $query the query string to invoke on the scope URL
     * @param string $accessToken The access token required for reading. Not required if the user is logged in
     * @param string $accessTokenSecret The private key required for reading. Not required if user is currently logged in
     * @return string the results of the read request xml string
     * @throws Exception Throws exception like 404, read errors, etc
     */
    public function read($scope, $query, $accessToken = null, $accessTokenSecret = null)
    {
        try{
            //construct url
            $url = $scope . $query;
            if(empty($accessToken) || empty($accessTokenSecret))
            {
                $current_user = Rexume\Models\Auth\Authentication::currentUser();
                if(!empty($current_user))
                {
                    $accessToken = $current_user->oauthToken();
                    $accessTokenSecret = $current_user->oauthSecret();
                }
                else{ 
                    throw new Exception("Cannot perform oAuth read without both 'accessToken' and 'accessTokenSecret'");
                }
            }
            //recreate request with new signatures
            $this->oauthObject->reset();
            //get the default signatures //append access token signatures
            $signatures = $this->getSignatures();
            $signatures['oauth_token'] = $accessToken;
            $signatures['oauth_secret'] = $accessTokenSecret;

            $result = $this->oauthObject->sign(array(/* 'action' => 'POST', */ 'path' => $url, 'signatures'=> $signatures));
            $page = \getWebContent($result['signed_url']);
            //get the required id from the xml content
            return $page;
        }
        catch(\Exception $e)
        {
            throw new Exception("Unknown error encountered during read", -1, $e);
        }
    }
}


trait OAuthBase{
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
            //'action' => 'POST',
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
