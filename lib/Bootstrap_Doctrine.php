<?php
   	require_once(SITE_ROOT . DIRECTORY_SEPARATOR . 'lib/doctrine2-orm/lib/Doctrine/ORM/Tools/Setup.php');
	require_once(SITE_ROOT . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "Configuration.php");
	
	$lib = SITE_ROOT . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "doctrine2-orm";
	Doctrine\ORM\Tools\Setup::registerAutoloadGit($lib);
	
	use Doctrine\ORM\Tools\Setup;
	use Doctrine\ORM\EntityManager;
	
	$paths = array("entities");
	$isDevMode = false;
	/**
	 * Contains web.config configuration parameters
	 */
	$appConfig = new \Rexume\Configuration\Configuration();
	
	$dbParams = array(
	    'driver' => 'pdo_mysql',
	    'user' => $appConfig->getDatabaseUser(),
	    'password' => $appConfig->getDatabasePassword(),
	    'dbname' => $appConfig->getDatabaseHost()
	);
	
	//check development mode
	if($appConfig->getDeploymentMode() == "Development")
	{
		define("DEVELOPMENT_ENVIRONMENT", true);
	}
	else {
		define("DEVELOPMENT_ENVIRONMENT", false);
	}
	
	
	//initialize database
	$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
	
	/**
	 * Database manager
	 */
	$entityManager = EntityManager::create($dbParams, $config);