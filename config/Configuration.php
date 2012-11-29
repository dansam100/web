<?php
namespace Rexume\Config;
/**
* Exception thrown when loading invalid configuration files
*/
class ConfigurationLoaderException extends \Exception{}

/**
    * Loader for web.config configuration parameters
    */
class Configuration extends \ProtoMapper\Config\ConfigLoader
{    
    const WEB_CONFIG = "xml/web.config.xml";
    
    private static $appConfig;

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
    private $restConfig;

    public function __construct()
    {
        $this->site_map = array();
        $this->config_location = CONFIG_FOLDER . DS . self::WEB_CONFIG;
        $this->loadConfig($this->config_location);
    }

    /**
     * Returns an instance that contains web.config configuration parameters
     * @return \Rexume\Config\Configuration
     */
    public static function getInstance()
    {
        if(isset(self::$appConfig)){
            return self::$appConfig;
        }
        else{
            return self::$appConfig = new Configuration();
        }
    }

    function loadConfig($configLocation)
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
            if(is_readable($dbconfig_path))
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
            if(!is_readable($sitemap_config))
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

        //LOAD: Get authentication style configs
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
        
        //LOAD: Load protocols for parsing data
        if(isset($this->xml->protocols['configuration'])){
            $protocols_config = join(DS, array(dirname($configLocation), $this->xml->protocols['configuration']));
            if(!is_readable($protocols_config))
            {
                throw new ConfigurationLoaderException("Protcols configuration file: '" . $protocols_config . " could not be found!");
            }
            $this->load($protocols_config);
        }
        
        //LOAD: load data configurations
        if(isset($this->xml->interfaces['configuration']))
        {
            $data_config = join(DS, array(dirname($configLocation), $this->xml->interfaces['configuration']));
            if(!is_readable($data_config)){
                throw new ConfigurationLoaderException("Protcols configuration file: '" . $data_config . " could not be found!");
            }
            
            $this->restConfig = new ReadConfiguration();
            $this->restConfig->load($data_config);
        }
    }
    
    /**
     * The unique site key for the current deployment
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }
    
    public function getInterfaceConfiguration()
    {
        return $this->restConfig;
    }
    
    /**
     * Gets a protocol definition configured for use when requesting information regarding user details
     * @param string $name The name of the protocol to fetch
     * @return ProtocolDefinition the protocol definition matching the data type
     */
    public function getDataProtocol($name)
    {
        return $this->protocols[$name]['Data'];
    }
    
    /**
     * Gets a protocol definition configured for use during authentication of a user
     * @param string $name The name of the protocol to fetch
     * @return ProtocolDefinition the protocol definition matching the authentication type
     */
    public function getAuthenticationProtocol($name)
    {
        return $this->protocols[$name]['Authentication'];
    }
    
    /**
     * Gets the set oAuth keys for a given provider specified by the name
     * @param string $name The name of the oAuth protocol to get the key for
     * @return AuthorizationKey
     */
    public function getAuthorizationKey($name)
    {
        if(isset($this->authentication[$name]))
        {
            return $this->authentication[$name];
        }
        return null;
    }
    
    /**
     * Gets the configured database host name
     * @return string
     */
    public function getDatabaseName()
    {
        if(isset($this->db_name))
        {
            return $this->db_name;
        }
        return null;
    }
    
    /**
     * Gets the configured database host name
     * @return string
     */
    public function getDatabaseHost()
    {
        if(isset($this->db_host))
        {
            return $this->db_host;
        }
        return null;
    }
    
    /**
     * Gets the configured database user
     * @return string
     */
    public function getDatabaseUser()
    {
        if(isset($this->db_user))
        {
            return $this->db_user;
        }
        return null;
    }
    
    /**
     * Gets the configured database password
     * @return string
     */
    public function getDatabasePassword()
    {
        if(isset($this->db_pw))
        {
            return $this->db_pw;
        }
        return null;
    }
    
    /**
     * Finds the path to the template folder for views
     * @return string
     */
    public function getTemplatesPath()
    {
        if(isset($this->templates))
        {
            return $this->templates;
        }
        return null;
    }
    
    /**
     * the configured deployment mode
     * @return string
     */
    public function getDeploymentMode()
    {
        if(isset($this->deployment_mode))
        {
            return $this->deployment_mode;
        }
        return null;
    }
    
    /**
     * Finds and returns the configured controller for the requested page
     * @param string $controller The name of the accessed controller
     * @return SiteMap the site map
     */
    public function getSiteMap($controller)
    {
        if(isset($this->site_map[$controller]))
        {
            return $this->site_map[$controller];
        }
        else if(isset($this->site_map['*']))
        {
            return $this->site_map['*'];
        }
        else return null;
    }
    
    /**
     * Finds and returns the default sitemap
     * @return SiteMap the default sitemap of the controller
     */
    public function getDefaultSiteMap()
    {
        if(isset($this->site_map[$this->default_sitemap]))
        {
            return $this->site_map[$this->default_sitemap];
        }
        else return null;
    }
}