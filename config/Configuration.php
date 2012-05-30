<?php
	namespace Rexume\Configuration;
	require_once(SITE_ROOT . DS . "lib" . DS . "Bootstrap.php");
	require_once("AuthorizationKey.php");
	require_once("Sitemap.php");
	
	define("CONFIG_ROOT", SITE_ROOT . DS . "config");
	
	/**
	 * Exception thrown when loading invalid configuration files
	 */
	class ConfigurationLoaderException extends \Exception{}
	
	/**
	 * Loader for web.config configuration parameters
	 */
    class Configuration
    {
    	const WEB_CONFIG = "web.config.xml";
		
		private $xml;

		private $templates;
    	private $db_name;
    	private $db_host;
    	private $db_user;
    	private $db_pw;
		private $deployment_mode;
		private $config_location;
		private $site_map;
		private $default_sitemap;
		private $siteKey;
		private $authentication;
		
		
		public function __construct()
    	{
	        $this->site_map = array();
	        $this->config_location = CONFIG_ROOT . DS . self::WEB_CONFIG;
			$this->LoadConfig($this->config_location);
    	}
		
	    function LoadConfig($configLocation)
	    {
	        if(!file_exists($configLocation))
	        {
	            throw new ConfigurationLoaderException("Web configuration file: '" . $configLocation . " could not be found!");
				
	        }
			//LOAD: the main configuration file
	        $this->xml = simplexml_load_file($configLocation);
			$this->templates = SITE_ROOT . DS . $this->xml->templates["location"];
			//TODO: make deployments loadable by type
			$this->deployment_mode = $this->xml->deployment["mode"];
			
			//LOAD: the database configuration file/section
			if(!isset($this->xml->database["configuration"]) && isset($this->xml->database))
			{	
		        $this->db_name = (string)$this->xml->database["name"];
		        $this->db_host = (string)$this->xml->database["host"];
		        $this->db_user = (string)$this->xml->database->username;
		        $this->db_pw = (string)$this->xml->database->password;
			}
			elseif(isset($this->xml->database["configuration"])) 
			{
				$dbconfig_path = join(DS, array(dirname($configLocation), $this->xml->database["configuration"]));
				if(file_exists($dbconfig_path))
				{
					$dbxml = simplexml_load_file($dbconfig_path);
					$this->db_name = (string)$dbxml->database["name"];
		        	$this->db_host = (string)$dbxml->database["host"];
		        	$this->db_user = (string)$dbxml->database->username;
		        	$this->db_pw = (string)$dbxml->database->password;
				}
				else {
					throw new ConfigurationLoaderException("Database configuration file: '$dbconfig_path' could not be found.");
				}
			}
			
			//LOAD: Site map configurations for redirecting to the right controllers and models
			if(isset($this->xml->sitemap["configuration"])){ 
				$sitemap_config = join(DS, array(dirname($configLocation), $this->xml->sitemap["configuration"]));
				if(!file_exists($sitemap_config))
		        {
		            throw new ConfigurationLoaderException("Controller configuration file: '" . $sitemap_config . " could not be found!");
		        }
				$sitemap_xml = simplexml_load_file($sitemap_config);
				$site_maps = $sitemap_xml->map;
				foreach ($site_maps as $map) {
					$default = false;
					if(isset($map['default']))
					{
						$this->default_sitemap = (string)$map['name'];
						$default = true;
					}
					$this->site_map[(string)$map['name']] = new SiteMap(
						(string)$map->controller, 
							(string)$map->model, 
								(string)$map->view, 
									(string)$map->defaultAction, $default
					);
				}
			}
			//LOAD: siteKey for deployment mode
			$deployment = $this->xml->xpath("deployment[@mode='$this->deployment_mode']");
			$this->siteKey = $deployment[0]->siteKey;
			
			//Get authentication style configs
			$auths = $this->xml->xpath("deployment[@mode='$this->deployment_mode']/authentication");
			foreach($auths as $auth)
			{
				$this->authentication[(string)$auth['name']] = new AuthorizationKey(
					(string)$auth['name'], 
					(string)$auth->apiKey, 
					(string)$auth->sharedSecret,
					(string)$auth->apiRoot,
					(string)$auth->requestToken,
					(string)$auth->authorizeToken,
					(string)$auth->accessToken,
					(string)$auth->scope,
					(string)$auth->callback
				);
			}
	    }

		public function getSiteKey()
		{
			return $this->siteKey;
		}
		
		public function getAuthorizationKey($name)
		{
			if(isset($this->authentication[$name]))
			{
				return $this->authentication[$name];
			}
			return null;
		}
		
		public function getDatabaseName()
		{
			if(isset($this->db_name))
			{
				return $this->db_name;
			}
			return null;
		}
		
		public function getDatabaseHost()
		{
			if(isset($this->db_host))
			{
				return $this->db_host;
			}
			return null;
		}
		
		public function getDatabaseUser()
		{
			if(isset($this->db_user))
			{
				return $this->db_user;
			}
			return null;
		}
		
		public function getDatabasePassword()
		{
			if(isset($this->db_pw))
			{
				return $this->db_pw;
			}
			return null;
		}
		
		public function getTemplatesPath()
		{
			if(isset($this->templates))
			{
				return $this->templates;
			}
			return null;
		}
		
		public function getDeploymentMode()
		{
			if(isset($this->deployment_mode))
			{
				return $this->deployment_mode;
			}
			return null;
		}
		
		public function getSiteMap($controller)
		{
			if(isset($this->site_map[$controller]))
			{
				return $this->site_map[$controller];
			}
			else return null;
		}
		
		public function getDefaultSiteMap()
		{
			if(isset($this->site_map[$this->default_sitemap]))
			{
				return $this->site_map[$this->default_sitemap];
			}
			else return null;
		}
    }