<?php
namespace Rexume\Configuration;
require_once("ProtocolObject.php");
require_once( "ProtocolBind.php");

/**
 * Description of Protocol
 *
 * @author sam.jr
 */
class ProtocolDefinition implements \Rexume\Parsers\IValueParser
{
    protected $name;
    protected $type;
    protected $query;
    protected $scope;
    protected $contenttype;
    protected $objects;
    protected $mappings;
    protected $parser = 'Rexume\Parsers\XMLSimpleParser';
    
    /**
     *
     * @param string $name the source
     * @param string $type the target 
     * @param string $contenttype The content type for the protocol
     * @param string $scope the scope to perform operations on
     * @param string $query the query fields to pass into the request
     * @param ProtocolObject[] $objects the objects to create
     * @param ProtocolMapping[] $mappings the mapping assocations related to the protocol
     * @param IParser $parser The parser to use for reading data contents
     */
    public function __construct($name, $type, $contenttype, $scope = null, $query = null, $objects = null, $mappings = null, $parser = null) {
        $this->type = $type;
        $this->name = $name;
        $this->scope = $scope;
        $this->query = $query;
        $this->contenttype = $contenttype;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        $this->mappings = array();
        foreach($mappings as $mapping){
            //$mapping->parent($this);
            $this->mappings[$mapping->name()] = $mapping;
        }
        $this->objects = array();
        foreach($objects as $object){
            $object->parent($this);
            $this->objects[$object->name()] = $object;
        }
    }
    
    /**
     * Get a ProtocolMapping related by name
     * @param string $name
     * @return ProtocolMapping The resulting mapping 
     */
    public function getMappingByName($name)
    {
        if(!empty($this->mappings[$name])){
            return $this->mappings[$name];
        }
        return null;
    }
    
    /**
     * Gets a given ProtocolObject by name
     * @param string $name
     * @return ProtocolMapping The resulting mapping 
     */
    public function getObject($name)
    {
        if(!empty($this->objects[$name])){
            return $this->objects[$name];
        }
        return null;
    }
    
    public function parseForUser(\User $user, $data)
    {
        
    }
    
    /**
     * Parses xml data containing relevant information
     * @param string $data the xml data
     * @return \Rexume\Models\Entity The results of the parse operation
     */
    public function parseOne($data)
    {
        $results = $this->parse($data);
        //return the first item in the list
        return (count($results) > 0) ? $results[0] : null;
    }
    
    /**
     * Parses xml data containing relevant information
     * @param string $data the xml data
     * @return \Rexume\Models\Entity[] The results of the parse operation
     */
    public function parse($data)
    {
        $parser_name = $this->parser;
        $parser = new $parser_name($this->objects, $this->type);
        return $parser->parse($data, $this);
    }
    
    /**
     * Constructs a string based query from a list of tokens
     * @param string $query
     * @param array $tokens
     * @return string
     */
    public function createQueryFromTokens($query, $tokens)
    {
        foreach($tokens as $tokenKey => $tokenValue){
            $query = preg_replace($tokenKey, $tokenValue, $query);
        }
        return $query;
    }
    
    /**
     * 
     * @return string protocol request query string
     */
    public function query()
    {
        return $this->query;
    }
    
    public function name()
    {
        return $this->name;
    }
    
    public function type()
    {
        return $this->type;
    }
    
    public function contentType()
    {
        return $this->contenttype;
    }
    
    public function scope()
    {
        return $this->scope;
    }
    
    public function sources()
    {
        return array_keys($this->objects);
    }
    
    public function targets()
    {
        return array_values($this->objects);
    }
}


/**
 * Trait for parsing protocol definitions
 */
trait ProtocolParser
{
    function createBinding(\SimpleXmlElement $bind)
    {
        return new \Rexume\Configuration\ProtocolBind
        (
            (string)$bind['source'], 
            (string)$bind['target'], 
            (string)$bind['type'],
            (string)$bind['name'],
            (string)$bind['default'],
            (string)$bind['parser'],
            array_map(array($this, 'createBinding'), $bind->xpath('data/bind'))
        );
    }
    
    
    /**
     * Parses a mapping xml definition for a given protocol
     * @param \SimpleXmlElement $mapping the list of mappings to parse
     * @return \Rexume\Configuration\ProtocolMapping The created protocol mapping
     */
    function createMapping(\SimpleXmlElement $mapping)
    {
        $protocol = null;
        $bindings = array_map(array($this, 'createBinding'), $mapping->xpath('bind'));
        if($mapping->read){
            $protocol = $this->parseProtocol
            (
                (string)$mapping->read['name'], 
                (string)$mapping->read['type'], 
                $mapping->read->definition,
                (string)$mapping->read['parser']
            );
        }
        return new \Rexume\Configuration\ProtocolObject
        (
            (string)$mapping['name'],
            (string)$mapping['type'],
            null,
            $protocol,
            $bindings,
            (string)$mapping['parser']
        );
    }
    
    /**
     * Parses a protocol xml file into respective protocol
     * @param \SimpleXMLElement $protocol_xml the xml defintion for a protocol
     * @return ProtocolDefintion[] a collection of parsed protocols
     */
    public function parseProtocols($protocol_xml)
    {
        $protocolDefs = $protocol_xml->definition;
        $result = array();
        //parse xml and create protocol and protocol mapping definitions
        foreach ($protocolDefs as $protocolDef) {
            foreach($protocolDefs->read as $readDef){
                $protocol = $this->parseProtocol
                    (
                        (string)$protocolDef['name'], 
                        (string)$protocolDef['type'], 
                        $readDef,
                        (string)$protocolDef['parser']
                    );
                if(!(array_key_exists($protocol->name(), $result))){
                    $result[$protocol->name()] = array();
                }
                
                $result[$protocol->name()][$protocol->contentType()] = $protocol;
            }
        }
        //var_dump($result['LinkedIn']['Data']->targets()[0]->bindings());
        return $result;
    }
    
    /**
     * Parses the xml configuration file to create protocol definitions used to read and parse data
     * @param string $name the name of the authentication scheme (eg: "LinkedIn", "Twitter", etc)
     * @param string $type the type represents the protocol type (eg: "REST", "FILE", etc)
     * @param \SimpleXMLElement $readDef the definition xml
     * @param string $parser the name of the parser class to use
     * @return \Rexume\Configuration\ProtocolDefinition the created protocol defintion
     */
    public function parseProtocol($name, $type, $readDef, $parser)
    {
        $objects = array_map(array($this, 'createMapping'), $readDef->xpath('object'));
        $mappings = array_map(array($this, 'createMapping'), $readDef->xpath('mappings/mapping'));        
        return new ProtocolDefinition
        (
            $name, 
            $type,
            (string)$readDef['contenttype'],
            (string)$readDef['scope'],
            (string)$readDef->query,
            $objects,
            $mappings,
            $parser
        );
    }
}
