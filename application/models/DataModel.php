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
     * @var Rexume\Config\ReadConfiguration 
     */
    protected $readConfiguration;
    
    protected $defaultWhere;
    
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
    public function setQuery($readType, $where = null, $isCollection = false){
        if(isset($where)){
            $whereValue = $this->defaultWhere;
            $whereValue['id'] = $where;
        }
        else $whereValue = $this->defaultWhere;
        $results = null;
        //TODO: use a DQL to only select relevant attributes
        if($isCollection){
            $results = \DB::get($readType->getBaseType(), $whereValue);
        }
        else{
            $results = \DB::getOne($readType->getBaseType(), $whereValue);
        }
        //var_dump($whereValue);
        //var_dump($results);
        $this->createOutput($results, $readType);
    }
    
    public function createOutput($entities, $readType){
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
    
    private function createDataObject($object){
        $class = get_class_name($object);
        if(empty($object) || is_scalar($object)){
            return $object;
        }
        else{
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
    
    private function setAttributes($dataObject, $entity, $attributes){
        foreach($attributes as $attribute){
            $target = $entity->{$attribute->getName()};
            if(!empty($target)){
                //slice the array if a limit is set on the attribute ref
                if(is_collection($target)){
                    if(count($target) > 0)
                    {
                        $limit = $attribute->getLimit();
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
        }
        return $dataObject;
    }
    
    public function objects(){
        return $this->objects;
    }
}
