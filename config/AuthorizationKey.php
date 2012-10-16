<?php
namespace Rexume\Config;

/*
* This class contains information about a given oAuth's protocols shared secret and api key
*/
class AuthorizationKey
{
    private $name;
    private $apiKey;
    private $sharedSecret;
    private $apiRoot;
    private $scope;
    private $callback;
    private $requestUrl;
    private $authorizeUrl;
    private $accessUrl;

    public function __construct($name, $apiKey, $sharedSecret, $apiRoot = "", $request = "", $authorize = "", $access = "", $scope = "", $callback = "")
    {
        $this->name = $name;
        $this->apiKey = $apiKey;
        $this->sharedSecret = $sharedSecret;
        $this->apiRoot = $apiRoot;
        $this->requestUrl = $apiRoot . $request;
        $this->accessUrl = $apiRoot . $access;
        $this->authorizeUrl = $apiRoot . $authorize;
        $this->scope = $apiRoot . $scope;
        $this->callback = $callback;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getSharedSecret()
    {
        return $this->sharedSecret;
    }

    public function getAccessTokenUrl()
    {
        return $this->accessUrl;
    }

    public function getAuthorizeTokenUrl()
    {
        return $this->authorizeUrl;
    }

    public function getRequestTokenUrl()
    {
        return $this->requestUrl;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function getApiRoot()
    {
        return $this->apiRoot;
    }
}