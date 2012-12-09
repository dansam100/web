<?php
namespace Rexume\Config;

/**
 * Description of DataObject
 *
 * @author sam.jr
 */
class DataObject {
    private $id;
    private $class;
    
    public function __construct($id = null, $class = null) {
        $this->id = $id;
        $this->class = $class;
    }
    
    /**
     * Overridden to allow dynamic creation of DataObjects properties
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    /**
     * Overridden to allow dynamic access of all internal variables
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name){
        return $this->$name;
    }
}
