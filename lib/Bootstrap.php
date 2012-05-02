<?php
    //bootstrap.php
    define("ENTITIES_FOLDER", SITE_ROOT . DIRECTORY_SEPARATOR . "application/models/entities/");
	require_once(SITE_ROOT . DIRECTORY_SEPARATOR . "application/controllers/Authentication.php");
    require_once(SITE_ROOT . DIRECTORY_SEPARATOR . "application/Util.php");
    foreach (directory_list_files(ENTITIES_FOLDER, "php") as $value) {
        require_once ENTITIES_FOLDER . "$value";
    }

    if (!class_exists("Doctrine\Common\Version", false))
    {
	    require_once(SITE_ROOT . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "Bootstrap_Doctrine.php");
	}
	if (!class_exists("Rexume\Models\Enum", false))
    {
	    require_once(SITE_ROOT . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "Enum.php");
	}
	require_once(SITE_ROOT . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "Shared_Setup.php");
