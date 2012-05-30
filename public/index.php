<?php
    define('SITE_ROOT', dirname(dirname(__FILE__)));
	define("DS", DIRECTORY_SEPARATOR);
	
	//start processing urls
	$ACCESSED_URL = null;
	if(isset($_GET['url']))
	{
		$ACCESSED_URL = $_GET['url'];
	}
	
	require_once(SITE_ROOT . DS . "lib" . DS . "Bootstrap.php");
?>