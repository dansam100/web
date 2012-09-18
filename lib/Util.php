<?php
/**
    * Function to list all files of a certain extension in a directory.
    */
function directory_list_files($dir, $ext = '*')
{
    $files = array();
    if(is_dir($dir))
    {
        if($handle = opendir($dir))
        {
            while(($file = readdir($handle)) !== false)
            {
                if($file != "." && $file != ".." && $file != "Thumbs.db")
                {
                    $exp = explode('.', $file);
                    end($exp);
                    $extension = $exp[key($exp)];

                    if($extension == $ext)
                    {
                        array_push($files, $file);
                    }
                }
            }
        }
        closedir($handle);
    }
    return $files;
}

/**
    * Lists of files in a folder recursively
    */
function directory_find_files($dir, $ext = '*') 
{ 
    $root = scandir($dir);
    $result = array();
    foreach($root as $value) 
    { 
        if($value === '.' || $value === '..') {continue;}
        $file = $dir . DS . $value; 
        if(is_file($file))
        {
            $exp = explode('.', $value);
            end($exp);
            $extension = $exp[key($exp)];
            if($extension == $ext)
            {
                $result[] = $file;
            }
            continue;
        }
        foreach(directory_find_files($file, $ext) as $value) 
        { 
            $result[] = $value; 
        } 
    } 
    return $result; 
}

/**
 * Gets the URL using CURL or fget.
 * @param string $url the url to access
 * @return string the parsed page
 */
function getWebContent($url)
{
	$page = null;
	if(ini_get('allow_url_fopen')) {
		$page = file_get_contents($url);
	}
	else{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $page = curl_exec($ch);
	    curl_close($ch);
	}
	
	return $page;
}

function getTokens($input, $regex)
{
    $result = array();
    preg_match('/' . $regex . '/', $input, $result);
    return $result;
}

function cast($obj, $to_class) {
  if(class_exists($to_class)) {
    $obj_in = serialize($obj);
    $obj_out = 'O:' . strlen($to_class) . ':"' . $to_class . '":' . substr($obj_in, $obj_in[2] + 7);
    return unserialize($obj_out);
  }
  else
    return false;
}