<?php
	namespace Rexume\Configuration;
	require_once "./Bootstrap.php";	
	
	class ConfigurationLoaderException extends \Exception{}


    class Configuration
    {
    	const WEB_CONFIG = "../../config/web.config.xml";
		private $xml;

		private $templates;
    	private $db_name;
    	private $db_host;
    	private $db_user;
    	private $db_pw;
		private $deployment_mode;
		
		
		public function __construct()
    	{
	        $this->LoadConfig();
    	}
		
	    function LoadConfig()
	    {
	        if(!file_exists(self::WEB_CONFIG))
	        {
	            throw new ConfigurationLoaderException("Web configuration file: '" . self::WEB_CONFIG . " could not be found!");
				
	        }
	        $this->xml = simplexml_load_file(self::WEB_CONFIG);
			
			$this->templates = $this->xml->templates["location"];
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
				$dbconfig_path = join(DIRECTORY_SEPARATOR, array(dirname(self::WEB_CONFIG), $this->xml->database["configuration"]));
				if(file_exists($dbconfig_path)){
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
	    }

		function getDatabaseHost()
		{
			if(isset($this->db_host))
			{
				return $this->db_host;
			}
			return null;
		}
		
		function getDatabaseUser()
		{
			if(isset($this->db_user))
			{
				return $this->db_user;
			}
			return null;
		}
		
		function getDatabasePassword()
		{
			if(isset($this->db_pw))
			{
				return $this->db_pw;
			}
			return null;
		}
		
		function getTemplatesPath()
		{
			if(isset($this->templates))
			{
				return $this->templates;
			}
			return null;
		}
		
		function getDeploymentMode()
		{
			if(isset($this->deployment_mode))
			{
				return $this->deployment_mode;
			}
			return null;
		}
    }