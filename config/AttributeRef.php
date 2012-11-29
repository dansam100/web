<?php
namespace Rexume\Config;
/**
 * Description of AttributeRef
 *
 * @author sam.jr
 */
class AttributeRef {
    protected $name;
    protected $limit;
    
    /**
     * 
     * @param string $name
     * @param int $limit
     */
    public function __construct($name, int $limit = null) {
        $this->name = $name;
        $this->limit = $limit;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getLimit(){
        return $this->limit;
    }
}
