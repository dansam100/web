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
    protected $type = 'Object';
    protected $isCollection = false;
    
    public function __construct($name, $type = 'Object', $isCollection = false) {
        $this->name = $name;
        if(!empty($this->type)){
            $this->type = $type;
        }
        if(!empty($isCollection)){
            $this->isCollection = $isCollection;
        }
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
}
