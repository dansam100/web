<?php
namespace Rexume\Application\Models;
use Rexume\Config\DataObject as DataObject;

/**
 * Description of DataModel
 *
 * @author sam.jr
 */
class DataModel extends Model
{
    /**
     *
     * @var User
     */
    protected $model;
    /**
     *
     * @var \Rexume\Config\ReadConfiguration 
     */
    protected $readConfiguration;
    
    protected $whereValue = array();
    protected $defaultWhere;
    protected $isCollection;
    /**
     *
     * @var \Rexume\Config\ReadType
     */
    protected $readType;
    /**
     *
     * @var \Rexume\Config\DataModel[]
     */
    protected $objects;


    public function __construct($user = null, $readConfiguration = null)
    {
        $this->model = $user;
        $this->objects = array();
        $this->readConfiguration = $readConfiguration;
        if(isset($this->model)){
            $this->defaultWhere = array('id' => $this->model->getId());
        }
    }
    
    /**
     * Set the underlying query for the data model
     * 
     * @param Rexume\Config\ReadType $type
     * @param int $where
     * @param boolean $isCollection
     */
    public function setQuery($readType, $filter, $where = null, $isCollection = false){
        $this->readType = $readType;
        if(isset($where)){
            $this->whereValue = $this->defaultWhere;
            $this->whereValue['id'] = $where;
        }
        if(isset($filter)){
            $this->whereValue = $filter->getWhereValue();
        }
        $this->isCollection = $isCollection;
        //every other class uses userId to refer to the user.id
        if(get_class_name($this->model) != $readType->getBaseType()){
            $this->whereValue['user'] = $this->defaultWhere['id'];
        }
        else $$this->whereValue = $this->defaultWhere;
    }
    
    public function getResults(){
        $results = null;
        //TODO: use a DQL to only select relevant attributes
        if($this->isCollection){
            $results = \DB::get($this->readType->getBaseType(), $this->whereValue);
        }
        else{
            $results = \DB::getOne($this->readType->getBaseType(), $this->whereValue);
        }
        $this->createOutput($results, $this->readType);
    }
    
    /**
     * Creates data objects from a given set of entities read in
     * @param Entity[] $entities
     * @param Rexume\Config\ReadType $readType
     */
    protected function createOutput($entities, $readType){
        $attributes = $readType->getAttributes();
        if(isset($entities)){
            if(is_collection($entities)){
                foreach($entities as $entity){
                    $dataObject = new DataObject($entity->getId(), $readType->getType());
                    $this->objects[] = $this->setAttributes($dataObject, $entity, $attributes);
                }
            }
            else{
                $dataObject = new DataObject($entities->getId(), $readType->getType());
                $this->objects[] = $this->setAttributes($dataObject, $entities, $attributes);
            }
        }
    }
    
    /**
     * Constructs a data object: primitive types are returned as is, datetime objects are cast to strings
     * @param \Entity $object
     * @return mixed a data object representing the created object
     */
    private function createDataObject($object){
        if(empty($object) || is_scalar($object)){
            return $object;
        }
        elseif($object instanceof \DateTime){
            return $object->format(DATE_ATOM);
        }
        else{
            $class = get_class_name($object);
            $dataObject = null;
            if(is_callable(array($object, 'getId'))){
                $dataObject = new DataObject($object->getId(), $class);
                $readType = $this->readConfiguration->getTypeByBase($class);
                return $this->setAttributes($dataObject, $object, $readType->getAttributes());
            }
            else{
                return $object;
            }
        }
    }
    
    /**
     * Sets a set of given attributes from a given Entity onto a DTO and returns the results
     * 
     * @param Rexume\Config\DataObject $dataObject
     * @param Entity $entity
     * @param Rexume\Config\AttributeRef[] $attributes
     * @return Rexume\Config\DataObject
     */
    private function setAttributes($dataObject, $entity, $attributes){
        foreach($attributes as $attribute){
            $target = $entity->{$attribute->getSource()};
            if(!empty($target)){
                if(is_collection($target)){
                    if(count($target) > 0){
                        $limit = $attribute->getLimit();
                        //slice the array if a limit is set on the attribute ref
                        if(isset($limit)){
                            $target = $target->slice(0, $limit);
                        }
                        $subObjects = array();
                        foreach($target as $value){
                            $subObjects[] = $this->createDataObject($value); 
                        }
                        $dataObject->{$attribute->getName()} = $subObjects;
                    }
                }
                else{
                    $dataObject->{$attribute->getName()} = $this->createDataObject($target);
                }
            }
            //set flags on the data object
            if($attribute->isAttribute()){
                $dataObject->setFlags($attribute->getName(), DataObject::IS_ATTRIBUTE);
            }
            if($attribute->isCollapsed()){
                $dataObject->setFlags($attribute->getName(), DataObject::IS_COLLAPSED);
            }
            if($attribute->isHidden()){
                $dataObject->setFlags($attribute->getName(), DataObject::IS_HIDDEN);
            }
        }
        return $dataObject;
    }
    
    /**
     * Resulting data objects
     * @return Rexume\Config\DataObject[]
     */
    public function objects(){
        return $this->objects;
    }
}
