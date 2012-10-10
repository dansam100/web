<?php
namespace Rexume\Parsers;
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
    
    private $parser;
    private $type;
    
    //format: USA, Canada(liberal), Canada(hard), UK
    private $postalCodeRegexes = array('/\d{5}(?(?=-)-\d{4})/', '/[A-Z]\d[A-Z] \d[A-Z]\d/', '/[ABCEGHJKLMNPRSTVXY]\d[A-Z] \d[A-Z]\d/', '/[A-Z]{1,2}\d[A-Z\d]? \d[ABD-HJLNP-UW-Z]{2}/');
    private $poBoxRegexes = array('/\bp(ost)?[.\s-]+o(ffice)?[.\s-]+box\b/');
        
    public function __construct($mappings, $type, $delimiters = array(',', '\n')) {
        $this->parser = new CompoundDelimitedParser($mappings, 'string', $delimiters);
        $this->type = $type;
    }
    
    public function parse($content, $callback)
    {
        $results = parent::parse($content, $callback);
        foreach($results as $value){
            $type = $this->checkType($value);
            switch($type){
                case AddressFieldType::get()->STREET1:
                    break;
                case AddressFieldType::get()->STREET2:
                    break;
                case AddressFieldType::get()->CITY:
                    break;
                case AddressFieldType::get()->PROVINCE:
                    break;
                case AddressFieldType::get()->POSTALCODE:
                    break;
                case AddressFieldType::get()->COUNTRY;
                    break;
                default:
                    break;
            }
        }
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
            elseif(count($pieces) > 1){{
                
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

?>
