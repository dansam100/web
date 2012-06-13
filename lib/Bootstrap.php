<?php
//bootstrap.php
define("CONTROLLERS_FOLDER", SITE_ROOT . DS . "application" . DS . "controllers");
define("MODELS_FOLDER", SITE_ROOT . DS . "application" . DS . "models");
define("VIEWS_FOLDER", SITE_ROOT . DS . "application" . DS . "views");
define("ENTITIES_FOLDER", MODELS_FOLDER . DS . "entities");
define("LIBRARIES_FOLDER", SITE_ROOT . DS . "lib");

require_once(LIBRARIES_FOLDER . DS . "Authentication.php");
require_once(LIBRARIES_FOLDER . DS . "LinkedInAuth.php");
require_once(LIBRARIES_FOLDER . DS . "Util.php");

//load all parsers
foreach (directory_list_files(LIBRARIES_FOLDER . DS . "parsers", "php") as $value) {
    require_once(LIBRARIES_FOLDER . DS . "parsers" . DS . "$value");
}

//load all controllers
foreach (directory_list_files(CONTROLLERS_FOLDER, "php") as $value) {
    require_once(CONTROLLERS_FOLDER . DS . "$value");
}
//load all models
foreach (directory_list_files(MODELS_FOLDER, "php") as $value) {
    require_once(MODELS_FOLDER . DS . "$value");
}
//load all views
foreach (directory_find_files(VIEWS_FOLDER, "php") as $value) {
    require_once("$value");
}
//load all doctrine models
foreach (directory_list_files(ENTITIES_FOLDER, "php") as $value) {
    require_once(ENTITIES_FOLDER . DS . "$value");
}
//load enum library
require_once(SITE_ROOT . DS . "lib" . DS . "Enum.php");
if (!class_exists("Doctrine\Common\Version", false))
{
    require_once(SITE_ROOT . DS . "lib" . DS . "Bootstrap_Doctrine.php");
}
//start the bootstraping
require_once(SITE_ROOT . DS . "lib" . DS . "Shared_Setup.php");