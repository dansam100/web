<?php
    define("ROOT", dirname(dirname(__FILE__)));
    require_once(ROOT . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "Bootstrap.php");
	
	//start processing urls
	$ACCESSED_URL = null;
	if(isset($_GET['url']))
	{
		$ACCESSED_URL = $_GET['url'];
	}
    //finish the bootstraping
    require_once(ROOT . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . 'Setup.php');
?>