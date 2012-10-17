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
                        array_push($files, $dir . DS . $file);
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
 * find files matching a pattern
 * using PHP "glob" function and recursion
 *
 * <a href="http://twitter.com/return">@return</a> array containing all pattern-matched files
 *
 * <a href="http://twitter.com/param">@param</a> string $dir     - directory to start with
 * <a href="http://twitter.com/param">@param</a> string $pattern - pattern to glob for
 */
function find($dir, $pattern){
    // escape any character in a string that might be used to trick
    // a shell command into executing arbitrary commands
    $dir = escapeshellcmd(str_replace('\\', '/', $dir));
    //$dir = escapeshellcmd($dir);
    // get a list of all matching files in the current directory
    $files = glob("$dir/$pattern");
    //echo "<br>Searching $dir for $pattern<br>";
    // find a list of all directories in the current directory
    // directories beginning with a dot are also included
    foreach (glob("$dir/{.[^.]*,*}", GLOB_BRACE|GLOB_ONLYDIR) as $sub_dir){
        $arr   = find($sub_dir, $pattern);  // resursive call
        $files = array_merge($files, $arr); // merge array with files from subdirectory
    }
    // return all found files
    return $files;
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

function cast($obj, $to_class)
{
    if($to_class == 'string')
    {
        return (string)$obj;
    }
    elseif($to_class == 'integer' || $to_class == 'int'){
        return (int)((string)$obj);
    }
    elseif($to_class == 'float' || $to_class == 'double'){
        return (float)((string)$obj);
    }
    elseif($to_class == 'boolean' || $to_class == 'bool'){
        $val = (string)$obj;
        switch (strtolower($val)){
            case "true":
            case "1":
                return true;
            case "false":
            case "0":
                return false;
            default:
                return empty($val);
        }
    }
    elseif(class_exists($to_class))
    {
        $obj_in = serialize($obj);
        $obj_out = 'O:' . strlen($to_class) . ':"' . $to_class . '":' . substr($obj_in, $obj_in[2] + 7);
        return unserialize($obj_out);
    }
    else return false;
}

function get_class_name($object = null)
{
    if (!is_object($object) && !is_string($object)){
        return false;
    }
    $class = explode('\\', (is_string($object) ? $object : get_class($object)));
    return $class[count($class) - 1];
}

function is_collection($var)
{
    if(is_array($var)){
        return true;
    }
    elseif(get_class_name($var) == 'ArrayCollection'){
        return true;
    }
    else{
        return false;
    }
}

function collection_add($collection, $value){
    if(is_array($collection)){
        array_push($collection, $value);
    }
    elseif(get_class_name($collection) == 'ArrayCollection'){
        $collection->add($value);
    }
    else{
        $collection = $value;
    }
}

function is_scalar_type($type = null){
    if(!isset($type)){
        return false;
    }
    return in_array($type, array('string', 'boolean', 'bool', 'integer', 'int', 'float', 'double'));
}

function str_starts_with($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function str_ends_with($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}