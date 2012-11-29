<?php
namespace Rexume\Config;

/**
 * Description of ReadType
 *
 * @author sam.jr
 */
class ReadType {
    protected $class;
    protected $base;
    protected $attributes;
    /**
     * Ctor
     * @param string $class
     * @param string $base
     * @param AttributeRef[] $attributes
     */
    public function __construct($class, $base, $attributes) {
        $this->class = $class;
        $this->base = $base;
        $this->attributes = $attributes;
    }
    
    public function getType(){
        return $this->class;
    }
    
    public function getBase(){
        return $this->base;
    }
    
    public function getAttributes(){
        return $this->attributes;
    }
}
