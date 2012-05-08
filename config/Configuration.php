<?php
	namespace Rexume\Configuration;
	require_once(SITE_ROOT . DS . "lib" . DS . "Bootstrap.php");
	
	define("CONFIG_ROOT", SITE_ROOT . DS . "config");
	
	class ConfigurationLoaderException extends \Exception{}
	
	class SiteMap{
		private $controller;
		private $model;
		private $view;
		private $defaultAction;	
		
		public function __construct($controller, $model = null, $view = null, $defaultAction = null)
		{
			$this->controller = $controller;
			$this->model = $model;
			$this->view = $view;
			$this->defaultAction = $defaultAction;
		}
		
		public function getController()
		{
			return $this->controller;
		}
		
		public function getModel()
		{
			return $this->model;
		}
		
		public function getView()
		{
			return $this->view;
		}
		
		public function getDefaultAction()
		{
			return $this->defaultAction;
		}
	}


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
	        $this->xml = simplexml_load_file($configLocation);
			
			$this->templates = SITE_ROOT . DS . $this->xml->templates["location"];
			$this->deployment_mode = $this->xml->deployment["mode"];
			
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
			if(isset($this->xml->sitemap["configuration"])){ 
				$sitemap_config = join(DS, array(dirname($configLocation), $this->xml->sitemap["configuration"]));
				if(!file_exists($sitemap_config))
		        {
		            throw new ConfigurationLoaderException("Controller configuration file: '" . $sitemap_config . " could not be found!");
		        }
				$sitemap_xml = simplexml_load_file($sitemap_config);
				$site_maps = $sitemap_xml->map;
				foreach ($site_maps as $map) {
					$this->site_map[(string)$map['name']] = new SiteMap((string)$map->controller, (string)$map->model, (string)$map->view, (string)$map->defaultAction);
				}
			}
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
    }