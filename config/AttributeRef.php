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
    protected $attribute;
    protected $collapse;
    protected $hidden;
    
    /**
     * 
     * @param string $name
     * @param int $limit
     */
    public function __construct($name, int $limit = null, $attribute = false, $collapse = false, $hidden = false) {
        $this->name = $name;
        if(isset($limit)){
            $this->limit = $limit;
        }
        $this->attribute = \cast($attribute, 'boolean');
        $this->collapse = \cast($collapse, 'boolean');
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getLimit(){
        return $this->limit;
    }
    
    public function isCollapsed(){
        return $this->collapse;
    }
    
    public function isAttribute(){
        return $this->attribute;
    }
    
    public function isHidden(){
        return $this->hidden;
    }
}
