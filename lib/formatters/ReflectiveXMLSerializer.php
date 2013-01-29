<?php
namespace Rexume\Lib\Formatters;
use \Rexume\Application\Models\Enums\FlagsEnum as FlagsEnum;
use \Rexume\Application\Models\Enums\DefinedEnum as DefinedEnum;

/**
 * Description of DataObject
 *
 * @author sam.jr
 */
class ReflectiveXMLSerializer {
    private $object;
    private $classRef;
    private $properties = array();
    public $DATAOBJECT_FLAGS;
    public $DATAOBJECT_FLAGTYPES;
    
    public function __construct($object) {
        $this->object = $object;
        //define enum types
        $this->DATAOBJECT_FLAGS = new FlagsEnum("NONE", "IS_ATTRIBUTE", "IS_COLLAPSED");
        $this->DATAOBJECT_FLAGTYPES = new DefinedEnum(array("ATTRIBUTE" => 'attribute', "COLLAPSED" => 'collapsed'));
        //use reflection to grab necessary data
        $this->classRef = new \ReflectionClass(\get_class($object));
        foreach($this->classRef->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED) as $property){
            $this->properties[$property->getName()] = $property;
        }
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
        if(isset($this->$name)){
            return $this->$name;
        }
        return null;
    }
    
    public function isAttribute($attribute){
        $flags = $this->getAttributeFlags($attribute);
        return (($flags & $this->DATAOBJECT_FLAGS->IS_ATTRIBUTE) == $this->DATAOBJECT_FLAGS->IS_ATTRIBUTE);
    }
    
    public function isCollapsed($attribute){
        $flags = $this->getAttributeFlags($attribute);
        return (($flags & $this->DATAOBJECT_FLAGS->IS_COLLAPSED) == $this->DATAOBJECT_FLAGS->IS_COLLAPSED);
    }
    
    private function getDocComment($attribute){
        if(isset($this->properties[$attribute])){
            $attributeObj = $this->properties[$attribute];
            return $attributeObj->getDocComment();
        }
        else return null;
    }
    
    private function getAttributeFlags($attribute){
        $docComment = $this->getDocComment($attribute);
        $regex = "/@format=\{(?'flags'(?:(?:[a-z]+)[,]{0,1})*)\}/im";
        $matches = array();
        $result = $this->DATAOBJECT_FLAGS->NONE;
        if(preg_match($regex, $docComment, $matches)){
            $flags = $matches['flags'];
            $flagSets = explode(',', $flags);
            if(in_array($this->DATAOBJECT_FLAGTYPES->ATTRIBUTE, $flagSets)){
                $result = $this->DATAOBJECT_FLAGS->IS_ATTRIBUTE;
            }
            if(in_array($this->DATAOBJECT_FLAGTYPES->COLLAPSED, $flagSets)){
                $result |= $this->DATAOBJECT_FLAGS->IS_COLLAPSED;
            }
        }
        return $result;
    }
}
