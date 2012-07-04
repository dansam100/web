<?php
use Doctrine\ORM\Tools\Setup;

//bootstrap.php
define("CONTROLLERS_FOLDER", SITE_ROOT . DS . "application" . DS . "controllers");
define("MODELS_FOLDER", SITE_ROOT . DS . "application" . DS . "models");
define("VIEWS_FOLDER", SITE_ROOT . DS . "application" . DS . "views");
define("ENTITIES_FOLDER", MODELS_FOLDER . DS . "entities");
define("LIBRARIES_FOLDER", SITE_ROOT . DS . "lib");
define("CONFIG_FOLDER", SITE_ROOT . DS . "config");

require_once(LIBRARIES_FOLDER . DS . "Util.php");

//setup doctrine stuff
if (!class_exists("Doctrine\Common\Version", false))
{
    require_once(LIBRARIES_FOLDER . DS . 'doctrine2-orm/lib/Doctrine/ORM/Tools/Setup.php');
    Setup::registerAutoloadGit(LIBRARIES_FOLDER . DS . "doctrine2-orm");
}

//include other classes
require_once(CONFIG_FOLDER . DS . 'Configuration.php');
require_once(LIBRARIES_FOLDER . DS . "Authentication.php");
require_once(LIBRARIES_FOLDER . DS . "LinkedInAuth.php");
require_once(CONFIG_FOLDER . DS . 'EntityManager.php');

//load all parsers
foreach (directory_list_files(LIBRARIES_FOLDER . DS . 'parsers', 'php') as $value) {
    require_once(LIBRARIES_FOLDER . DS . "parsers" . DS . "$value");
}

//load all controllers
foreach (directory_list_files(CONTROLLERS_FOLDER, 'php') as $value) {
    require_once(CONTROLLERS_FOLDER . DS . "$value");
}
//load all models
foreach (directory_list_files(MODELS_FOLDER, 'php') as $value) {
    require_once(MODELS_FOLDER . DS . "$value");
}
//load all views
foreach (directory_find_files(VIEWS_FOLDER, 'php') as $value) {
    require_once("$value");
}
//load all doctrine models
foreach (directory_list_files(ENTITIES_FOLDER, 'php') as $value) {
    require_once(ENTITIES_FOLDER . DS . "$value");
}
//load enum library
require_once(LIBRARIES_FOLDER . DS . "Enum.php");

//check development mode
define("DEVELOPMENT_ENVIRONMENT", (\Rexume\Configuration\Configuration::getInstance()->getDeploymentMode() == "Development"));


//start the bootstraping
require_once(LIBRARIES_FOLDER . DS . 'Setup.php');