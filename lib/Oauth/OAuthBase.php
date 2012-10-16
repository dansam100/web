<?php
namespace Rexume\Lib\OAuth;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OAuthBase
 *
 * @author sam.jr
 */
class OAuthBase
{
    /**
     * Gets the default signatures required to make an oAuth acessToken call
     * @return array An array of signed keys
     */
    public function constructAccessSignature($oauthToken, $oauthSecret, $oauthVerifier)
    {
        $auth_key = \Rexume\Config\Configuration::getInstance()->getAuthorizationKey($this->name);
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
    public function constructAuthorizationSignature($oauthToken)
    {
        $auth_key = \Rexume\Config\Configuration::getInstance()->getAuthorizationKey($this->name);
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
    public function constructRequestSignature()
    {			
        $auth_key = \Rexume\Config\Configuration::getInstance()->getAuthorizationKey($this->name);
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
    public function getSignatures()
    {
        $auth_key = \Rexume\Config\Configuration::getInstance()->getAuthorizationKey($this->name);
        return array(
            'consumer_key' => $auth_key->getApiKey(), 
            'shared_secret' => $auth_key->getSharedSecret()
        );
    }
}

