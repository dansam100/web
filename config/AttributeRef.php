<?php
namespace Rexume\Config;
/**
 * Description of AttributeRef
 *
 * @author sam.jr
 */
class AttributeRef {
    protected $name;
    protected $source;
    protected $limit;
    protected $attribute;
    protected $collapse;
    protected $hidden;
    
    /**
     * 
     * @param type $name
     * @param type $source
     * @param int $limit
     * @param type $attribute
     * @param type $collapse
     * @param type $hidden
     */
    public function __construct($name, $source = null, int $limit = null, $attribute = false, $collapse = false, $hidden = false) {
        $this->name = $name;
        if(isset($limit)){
            $this->limit = $limit;
        }
        if(!empty($source)){
            $this->source = $source;
        }
        else $this->source = $name;
        $this->attribute = \cast($attribute, 'boolean');
        $this->collapse = \cast($collapse, 'boolean');
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getSource(){
        return $this->source;
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
