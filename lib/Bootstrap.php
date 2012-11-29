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
require_once(LIBRARIES_FOLDER . DS . "protomapper" . DS . "AutoLoad.php");

//setup doctrine stuff
if (!class_exists("Doctrine\Common\Version", false))
{
    require_once(LIBRARIES_FOLDER . DS . 'doctrine2-orm/lib/Doctrine/ORM/Tools/Setup.php');
    Setup::registerAutoloadGit(LIBRARIES_FOLDER . DS . "doctrine2-orm");
}
$loaders = array(
    new \Doctrine\Common\ClassLoader('Rexume\Lib', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Config', BASE_DIR),
    new \Doctrine\Common\ClassLoader('*', CONFIG_FOLDER, true),
    new \Doctrine\Common\ClassLoader('*', ENTITIES_FOLDER, true),
    new \Doctrine\Common\ClassLoader('Rexume\Lib\Parsers', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Application\Models', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Application\Controllers', BASE_DIR),
    new \Doctrine\Common\ClassLoader('Rexume\Application\Views', BASE_DIR, true),
    new \Doctrine\Common\ClassLoader('Rexume\Application\Models\Enums', BASE_DIR, true),
    new \Doctrine\Common\ClassLoader('Rexume\Lib\OAuth', LIBRARIES_FOLDER . DS . "OAuth", true)
);
foreach($loaders as $loader){ $loader->register(); };
//include all entity files
foreach(find(ENTITIES_FOLDER, '*') as $file){ require_once $file; }

//check development mode
define("DEVELOPMENT_ENVIRONMENT", (Rexume\Config\Configuration::getInstance()->getDeploymentMode() == "Development"));