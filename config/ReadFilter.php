<?php
namespace Rexume\Config;

/**
 * Description of ReadFilter
 *
 * @author sam.jr
 */
class ReadFilter {
    protected $name;
    protected $attribute;
    protected $value;
    protected $whereValue = array();
    
    public function __construct($name, $attribute, $value) {
        $this->name = $name;
        $this->attribute = $attribute;
        $this->value = $value;
        $this->whereValue[$attribute] = $value;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getAttribute(){
        return $this->attribute;
    }
    
    public function getValue(){
        return $this->value;
    }
    
    public function getWhereValue(){
        return $this->whereValue;
    }
}
