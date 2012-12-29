<?php
namespace Rexume\Config;

/**
 * Description of DataObject
 *
 * @author sam.jr
 */
class DataObject {
    private $properties = array();
    //constants
    const NONE = 0;
    const IS_ATTRIBUTE = 1;
    const IS_COLLAPSED = 2;
    const IS_HIDDEN = 4;
    const DATAOBJECT_ATTRIBUTE = 'attribute';
    const DATAOBJECT_COLLAPSED = 'collapsed';
    const DATAOBJECT_HIDDEN = 'hidden';
    
    public function __construct($id, $class) {
        $this->properties = array();
        $this->id = $id;
        $this->class = $class;
        $this->setFlags("id", self::IS_ATTRIBUTE);
        $this->setFlags("class", self::IS_HIDDEN);
    }
    
    /**
     * Overridden to allow dynamic creation of DataObjects properties
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value){
        $this->$name = $value;
        if(!isset($this->properties[$name])){
            $this->properties[$name] = self::NONE;
        }
    }
    
    public function setFlags($attribute, $flags){
        if(isset($this->properties[$attribute])){
            if($flags !== self::NONE){
                $this->properties[$attribute] |= $flags;
            }
            else $this->properties[$attribute] &= $flags;
        }
    }
    
    /**
     * Overridden to allow dynamic access of all internal variables
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name){
        if(isset($this->$name)){
            return $this->$name;
        }
        return null;
    }
    
    public function isAttribute($attribute){
        $flags = $this->properties[$attribute];
        return (($flags & self::IS_ATTRIBUTE) == self::IS_ATTRIBUTE);
    }
    
    public function isCollapsed($attribute){
        $flags = $this->properties[$attribute];
        return (($flags & self::IS_COLLAPSED) == self::IS_COLLAPSED);
    }
    
    public function isHidden($attribute){
        $flags = $this->properties[$attribute];
        return (($flags & self::IS_HIDDEN) == self::IS_HIDDEN);
    }
}
