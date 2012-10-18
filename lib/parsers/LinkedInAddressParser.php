<?php
namespace Rexume\Lib\Parsers;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LinkedInAddressParser
 *
 * @author sam.jr
 */
class LinkedInAddressParser
{
    private $street1;
    private $street2;
    private $city;
    private $province;
    private $postalCode;
    private $country;
    private $mappings;
    private $type;
    
    //format: USA, Canada(liberal), Canada(hard), UK
    private $postalCodeRegexes = array('/\d{5}(?(?=-)-\d{4})/', '/[A-Z]\d[A-Z][\s]*\d[A-Z]\d/', '/[ABCEGHJKLMNPRSTVXY]\d[A-Z][\s]*\d[A-Z]\d/', '/[A-Z]{1,2}\d[A-Z\d]?[\s]*\d[ABD-HJLNP-UW-Z]{2}/');
    private $poBoxRegexes = array('/\bp(ost)?[.\s-]+o(ffice)?[.\s-]+box\b/');
    private $locationRegexes = array('/([\w])[^\S\n\r]+([\w])[\s,]+([a-zA-Z0-9]+)?[\s,]+([\w]+)?/');
    private $street1Regexes = array('/(.*)?(?:\w\w)\s+/');
        
    public function __construct($mappings, $type) {
        $this->mappings = $mappings;
        $this->type = $type;
    }
    
    /**
     * 
     * @param mixed $content
     * @param IParser $callback
     */
    public function parse($content, $callback)
    {
        $string = $callback->getValue($content, ".");
        $this->street1 = $this->getMatch($string, $this->street1Regexes);
        $this->street2 = $this->getMatch($string, $this->poBoxRegexes);
        $this->city = $this->getMatch($string, $this->locationRegexes, 1);
        $this->province = $this->getMatch($string, $this->locationRegexes, 2);
        $this->postalCode = $this->getMatch($string, $this->postalCodeRegexes);
        $this->country = $this->getMatch($string, $this->locationRegexes, 4);
        $result = new $this->type;
        foreach($this->mappings as $mapping){
            $result->{$mapping->target()} = $this->{$mapping->target()};
        }
        return $result;
    }
    
    private  function getMatch($content, $regexes, $match = 0){
        foreach($regexes as $regex){
            if(preg_match($regex, $content, $matches)){
                return trim($matches[$match]);
            }
        }
        return null;
    }


    private function checkType($value){
        //match post office boxes
        foreach($this->poBoxRegexes as $poBoxRegex){
            if(preg_match($poBoxRegex, $value)){
                return AddressFieldType::get()->STREET2;
            }
        }
        $pieces = preg_split('/[\s]+/', $value, PREG_SPLIT_NO_EMPTY);
        if(count($pieces) > 0){
            $first = $pieces[0];
            //if the first part is a number, it's street1
            if(cast($first, 'integer')){
                return AddressFieldType::get()->STREET1;
            }
            elseif(count($pieces) > 1){
                
            }
        }
    }
}


/**
* Authentication state flags
*/
class AddressFieldType
{
    private static $address_field_type;
    private $types;
    public function __construct() {
        $this->types = new \Rexume\Models\Enums\Enum("STREET1", "STREET2", "CITY", "PROVINCE", "POSTALCODE", "COUNTRY", "NONE");
    }
    
    public function __get(/*string*/ $name)
    {
        return $this->types->$name;
    }
    
    public static function get()
    {
        if(isset(self::$address_field_type))
        {
            return self::$address_field_type;
        }
        else return self::$address_field_type = new AddressFieldType();
    }
}