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
if (!class_exists("Doctrine\Common\Version", false)){
    require_once(LIBRARIES_FOLDER . DS . 'doctrine2-orm/lib/Doctrine/ORM/Tools/Setup.php');
    Setup::registerAutoloadGit(LIBRARIES_FOLDER . DS . "doctrine2-orm");
}
$loaders = array(
    new ClassLoader('*', CONFIG_FOLDER, true),
    new ClassLoader('Rexume\Config', BASE_DIR),
    new ClassLoader('*', ENTITIES_FOLDER, true),
    new ClassLoader('Rexume\Lib\OAuth', BASE_DIR),
    new ClassLoader('Rexume\Lib', BASE_DIR, true),
    new ClassLoader('Rexume\Lib\Parsers', BASE_DIR),
    new ClassLoader('Rexume\Lib\Readers', BASE_DIR),
    new ClassLoader('Rexume\Lib\Formatters', BASE_DIR),
    new ClassLoader('Rexume\Lib\Authentication', BASE_DIR),
    new ClassLoader('Rexume\Application\Models', BASE_DIR),
    new ClassLoader('Rexume\Application\Controllers', BASE_DIR),
    new ClassLoader('Rexume\Application\Views', BASE_DIR, true),
    new ClassLoader('Rexume\Application\Models\Enums', BASE_DIR, true)
);
foreach($loaders as $loader){ $loader->register(); };

//check development mode
define("DEVELOPMENT_ENVIRONMENT", (Rexume\Config\Configuration::getInstance()->getDeploymentMode() == "Development"));