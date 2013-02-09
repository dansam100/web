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
    
    public function __construct($types = array(), $interfaces = array()) {
        $this->types = $types;
        $this->interfaces = $interfaces;
    }
    
    public function getDefaultQuery(){
        return ".";
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
        $interfaceNodes = $data_config_xml->xpath('//interfaces');
        foreach($interfaceNodes as $interfaceNode)
        {
            $scope = (string)$interfaceNode['scope'];
            $this->interfaces[$scope] = array();
            foreach($interfaceNode->interface as $interface)
            {
                $name = (string)$interface['name'];
                $this->interfaces[$scope][$name] = new ReadInterface
                    (
                        $name,
                        (string)$interface['type'],
                        (bool)$interface['collection'],
                        array_map(
                            function($filterConfig){
                                return new ReadFilter((string)$filterConfig['name'], (string)$filterConfig['attribute'], (string)$filterConfig['value']);
                            },
                            $interface->xpath('filter')
                        )
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
                        return new AttributeRef((string)$item['name'], (string)$item['source'], $item['limit'], $item['attribute'], $item['collapse'], $item['hidden']);
                    },
                    $type->xpath('attribute')
                )
            );
        }
    }
}
