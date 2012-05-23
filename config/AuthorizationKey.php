<?php
	namespace Rexume\Configuration;
	
	/*
	 * This class contains information about a given oAuth's protocols shared secret and api key
	 */
	class AuthorizationKey
	{
		private $name;
		private $apiKey;
		private $sharedSecret;
		
		public function __construct($name, $apiKey, $sharedSecret)
		{
			$this->name = $name;
			$this->apiKey = $apiKey;
			$this->sharedSecret = $sharedSecret;
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
	}