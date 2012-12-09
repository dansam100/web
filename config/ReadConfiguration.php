<?php
namespace Rexume\Config;

/**
 * Description of ReadConfiguration
 *
 * @author sam.jr
 */
class ReadConfiguration {
    protected $interfaces;
    protected $types;
    
    public function __construct($types = null, $interfaces = null) {
        $this->types = $types;
        $this->interfaces = $interfaces;
    }
    
    public function getInterface($scope, $name){
        if(isset($this->interfaces[$scope][$name]))
        {
            return $this->interfaces[$scope][$name];
        }
        return null;
    }
    
    public function getType($name){
        if(isset($this->types[$name]))
        {
            return $this->types[$name];
        }
        return null;
    }
    
    public function getTypes(){
        if(isset($this->types)){
            return array_values($this->types);
        }
        return null;
    }
    
    public function getTypeByBase($base){
        if(isset($this->types)){
            foreach($this->types as $type){
                if($type->getBaseType() == $base){
                    return $type;
                }
            }
        }
        return null;
    }
    
    public function load($file_config){
        $data_config_xml = simplexml_load_file($file_config);
        $this->interfaces = array(); $this->types = array();
        $interfaceNodes = $data_config_xml->xpath('//interfaces');
        foreach($interfaceNodes as $interfaceNode)
        {
            $scope = (string)$interfaceNode['scope'];
            $this->interfaces[$scope] = array();
            foreach($interfaceNode->xpath('//interface') as $interface)
            {
                $name = (string)$interface['name'];
                $this->interfaces[$scope][$name] = new ReadInterface
                    (
                        (string)$interface['name'],
                        (string)$interface['type'],
                        $interface['isCollection']
                    );
            }
        }
        foreach($data_config_xml->xpath('//types/object') as $type)
        {
            $this->types[(string)$type['class']] = new ReadType
            (
                (string)$type['class'],
                (string)$type['base'],
                array_map(
                    function($item){
                        if(isset($item->limit)){ 
                            return new AttributeRef((string)$item['name'], (int)$item['limit']);    
                        }
                        else{
                            return new AttributeRef((string)$item['name']);
                        }
                    },
                    $type->xpath('attribute')
                )
            );
        }
    }
}
