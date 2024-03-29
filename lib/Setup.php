<?php
/** Check if environment is development and display errors **/
function setReporting()
{
	if(DEVELOPMENT_ENVIRONMENT) {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	} 
    else {
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', SITE_ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
	}
}

/** Check for Magic Quotes and remove them **/
function stripSlashesDeep($value)
{
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes()
{
	if(get_magic_quotes_gpc()){
		$_GET    = stripSlashesDeep($_GET   );
		$_POST   = stripSlashesDeep($_POST  );
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/
function unregisterGlobals() {
    if(ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Main Call Function **/
function callHook() {
	global $ACCESSED_URL;
	$appConfig = \Rexume\Config\Configuration::getInstance();
	
	$siteMap = null;
	if(isset($ACCESSED_URL))
	{
		//blow up the url and grab relevant information
		$urlArray = explode("/", $ACCESSED_URL);
		$siteMap = $appConfig->getSiteMap($urlArray[0]);
		array_shift($urlArray);
	}
	else $siteMap = $appConfig->getDefaultSiteMap();
	
	if(isset($siteMap))
	{
		$authentication = new \Rexume\Lib\Authentication\Authentication();
		if(!(isset($_SESSION['userId']) && $authentication->validateSession()))
		{
			//invalid login. if user is not accessing default site login page, redirect to it
			if(!$siteMap->isDefault())
			{
				//TODO: fix navigation so that it goes to root/controller at all times
                header("location: /login");
			}
		}	
	
		$action = null;
		$queryString = array();
		if(isset($urlArray[0])){
			$action = $urlArray[0];
			array_shift($urlArray);
			$queryString = $urlArray;
		}
		else $action = $siteMap->getDefaultAction();
		
		$controller = $siteMap->getController();
		$dispatch = new $controller($siteMap->getModel(), $siteMap->getView(), $action);
		if (is_callable(array($dispatch, $action))) {
			call_user_func_array(array($dispatch, $action), $queryString);
		}
		else {
			//Error page Generation Code Here
		}
	}
}

setReporting();
removeMagicQuotes();
unregisterGlobals();

/* PROGRAM STARTS */
session_start();
callHook();
