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
    
    /**
     * Overridden to allow dynamic creation of DataObjects properties
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value){
        $this-$name = $value;
    }
}
