<?php
namespace Rexume\Lib\Readers;
use Rexume\Lib\Authentication\Authentication as Authentication;
use Rexume\Lib\OAuth;
/**
 * Description of oAuthReader
 *
 * @author sam.jr
 */
class OAuthReader extends OAuth\OAuthBase
{
    protected $name;
    protected $oauthObject;
    
    /**
     * Constructor
     * @param string  $name The name of the reader. Used to retrieve protocols when necessary
     */
    public function __construct($name) {
        $this->name = $name;
        $this->oauthObject = new OAuth\OAuthSimple();
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
                $current_user = Authentication::currentUser();
                if(!empty($current_user))
                {
                    $accessToken = $current_user->oauthToken();
                    $accessTokenSecret = $current_user->oauthSecret();
                }
                else{ 
                    throw new \Exception("Cannot perform oAuth read without both 'accessToken' and 'accessTokenSecret'");
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
            throw new \Exception("Unknown error encountered during read", -1, $e);
        }
    }
}