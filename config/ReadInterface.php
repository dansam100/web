<?php
namespace Rexume\Config;
/**
 * Description of DataObject
 *
 * @author sam.jr
 */
class ReadInterface
{
    protected $name;    
    protected $type = 'Entity';
    protected $filters = array();
    protected $isCollection = false;
    
    public function __construct($name, $type = 'Entity', $isCollection = false, $filters = array()) {
        $this->name = $name;
        foreach($filters as $filter){
            $this->filters[$filter->getName()] = $filter;
        }
        if(!empty($this->type)){
            $this->type = $type;
        }
        if(!empty($isCollection)){
            $this->isCollection = $isCollection;
        }
    }
    
    public function getFilter($name){
        if(isset($this->filters[$name])){
            return $this->filters[$name];
        }
        return null;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getIsCollection()
    {
        return $this->isCollection;
    }
    
    public function getFilters()
    {
        return $this->filters;
    }
}
