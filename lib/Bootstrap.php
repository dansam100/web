<?php
use Doctrine\ORM\Tools\Setup;

//bootstrap.php
define("DS", DIRECTORY_SEPARATOR);
define('SITE_ROOT', dirname(dirname(__FILE__)));
define('BASE_DIR', dirname(SITE_ROOT));
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
$lookup = function($classFile, $folder){ return find($folder, $classFile);};
$loaders = array(
    new \Doctrine\Common\ClassLoader('*', BASE_DIR, $lookup),
    new \Doctrine\Common\ClassLoader('Rexume\Config', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Application\Controllers', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Application\Views', BASE_DIR, $lookup),
    new \Doctrine\Common\ClassLoader('Rexume\Application\Models', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Lib', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Lib\Parsers', BASE_DIR),
    new \Doctrine\Common\ClassLoader('\Rexume\Lib\OAuth', LIBRARIES_FOLDER . DS . "OAuth", $lookup)
);
foreach($loaders as $loader){ $loader->register(); };

//check development mode
define("DEVELOPMENT_ENVIRONMENT", (Rexume\Config\Configuration::getInstance()->getDeploymentMode() == "Development"));