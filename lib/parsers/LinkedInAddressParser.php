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
    
    private $parser;
    
    //format: USA, Canada(liberal), Canada(strict), UK
    private $postalCodeRegexes = array('/\b\d{5}(?(?=-)-\d{4})\b/i', '/\b[A-Z]\d[A-Z][\s]*\d[A-Z]\d\b/i', '/\b[ABCEGHJKLMNPRSTVXY]\d[A-Z][\s]*\d[A-Z]\d\b/i', '/\b[A-Z]{1,2}\d[A-Z\d]?[\s]*\d[ABD-HJLNP-UW-Z]{2}\b/i');
    private $poBoxRegexes = array('/\bp(ost)?[.\s-]?o(ffice)?[.\s-]+b(ox)?[\s]+[a-z0-9]+\b/i');
    private $locationRegexes = array('/^([a-z]+)[\s]+([a-z]+)[\s,]+([a-z0-9-]+)+$/i');
    private $countryRegexes = array('/^[^\s]+$/i');
    private $street1Regexes = array('/^(\d+[\s]*(-[\s]*[\d]+)?[\s]+[a-z]+([\s]+[a-z]+))+\b/i');
        
    public function __construct($mappings, $type) {
        $this->mappings = $mappings;
        $this->type = $type;
        $this->parser = new CompoundDelimitedParser(null, 'string');
    }
    
    /**
     * 
     * @param mixed $content
     * @param IParser $callback
     */
    public function parse($content, $callback)
    {
        $string = $callback->getValue($content, ".");
        //certain things can be matched right away
        $this->postalCode = $this->getMatch($string, $this->postalCodeRegexes);
        $this->street2 = $this->getMatch($string, $this->poBoxRegexes);
        //break the string into pieces
        $pieces = $this->parser->parse($string);
        //match the rest
        $this->street1 = $this->getMatch($pieces, $this->street1Regexes);
        $this->city = $this->getMatch($pieces, $this->locationRegexes, 1, $this->poBoxRegexes);
        $this->province = $this->getMatch($pieces, $this->locationRegexes, 2, $this->poBoxRegexes);
        $this->country = $this->getMatch($pieces, $this->countryRegexes);
        $result = new $this->type;
        foreach($this->mappings as $mapping){
            $result->{$mapping->target()} = $this->{$mapping->target()};
        }
        return $result;
    }
    
    private  function getMatch($content, $regexes, $match = 0, $exclude = array()){
        if(is_array($content)){
            foreach($content as $value){
                $result = $this->getMatch($value, $regexes, $match, $exclude);
                if(!empty($result)){
                    return $result;
                }
            }
        }
        else{
            foreach($regexes as $regex){
                $matches = array();
                $is_excluded = $this->getMatch($content, $exclude);
                if(empty($is_excluded) && preg_match($regex, $content, $matches)){
                    return trim($matches[$match]);
                }
            }
        }
        return null;
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