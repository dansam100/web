<?php
namespace Rexume\Configuration;
use Rexume\Parsers;
require_once("ProtocolMapping.php");

/**
 * Description of Protocol
 *
 * @author sam.jr
 */
class ProtocolDefinition {
    protected $name;
    protected $type;
    protected $query;
    protected $scope;
    protected $contenttype;
    protected $objects;
    protected $definitions;
    protected $parser = 'XMLSimpleParser';
    protected $map;
    
    /**
     *
     * @param string $name the source
     * @param string $type the target 
     * @param string $contenttype The content type for the protocol
     * @param string $scope the scope to perform operations on
     * @param string $query the query fields to pass into the request
     * @param ProtocolObject[] $objects the objects to create
     * @param ProtocolMapping[] $definitions the mapping assocations related to the protocol
     * @param Parser $parser The parser to use for reading data contents
     */
    public function __construct($name, $type, $contenttype, $scope = null, $query = null, $objects = null, $definitions = null, $parser = null) {
        $this->type = $type;
        $this->name = $name;
        $this->scope = $scope;
        $this->query = $query;
        $this->contenttype = $contenttype;
        $this->map = array();
        if(!empty($parser)){
            $this->parser = $parser;
        }
        $this->definitions = array();
        foreach($definitions as $mapping){
            array_push($this->definitions, $mapping);
            $map[$mapping->getSource()] = $mapping->getTarget();
            $protocol = $mapping->getProtocol();
            if(!empty($protocol) && empty($protocol->parser))
            {
                $protocol->parser = $this->parser;
            }
        }
        $this->objects = array();
        foreach($objects as $object){
            array_push($this->objects, $object);
        }
    }
    
    /**
     *
     * @param string $name
     * @return ProtocolMapping The resulting mapping 
     */
    public function getMappingBySource($name)
    {
        foreach($this->definitions as $definition)
        {
            if($definition->getSource() == $name)
            {
                return $definition;
            }
        }
        return null;
    }
    
    /**
     * Parses xml data containing relevant information
     * @param string $data the xml data
     * @return \Rexume\Models\Entity The results of the parse operation
     */
    public function parseOne(/*string*/ $data)
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
    public function parse(/*string*/ $data)
    {
        $parser_name = $this->parser;
        $callback = array($this, 'getMappingBySource');
        $parser = new $parser_name($data, $callback);
        return $parser->parse();
    }
    
    /**
     * 
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
    public function getQuery()
    {
        return $this->query;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getContentType()
    {
        return $this->contenttype;
    }
    
    public function getScope()
    {
        return $this->scope;
    }
    
    public function getSourceBindings()
    {
        return array_keys($this->map);
    }
    
    public function getTargetBindings()
    {
        return array_values($this->map);
    }
}


/**
 * Trait for parsing protocol definitions
 */
trait ProtocolParser
{
    /**
     * Parses a mapping xml definition for a given protocol
     * @param \SimpleXmlElement $mapping the list of mappings to parse
     * @return \Rexume\Configuration\ProtocolMapping The created protocol mapping
     */
    function createMapping(\SimpleXmlElement $mapping)
    {
        $bind_xml = $mapping->xpath('bind');
        $bindings = array(); $protocol = array();
        if($bind_xml){
            $bindings = array_map(array($this, 'createMapping'), $bind_xml);
        }
        if($mapping->read){
            $protocol = $this->parseProtocol
                (
                    (string)$mapping->read['name'], 
                    (string)$mapping->read['type'], 
                    $mapping->read->definition,
                    (string)$mapping->read['parser']
                );
        }
        return new \Rexume\Configuration\ProtocolMapping
        (
            (string)$mapping['source'],
            (string)$mapping['target'],
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
                if(!(array_key_exists($protocol->getName(), $result))){
                    $result[$protocol->getName()] = array();
                }
                
                $result[$protocol->getName()][$protocol->getContentType()] = $protocol;
            }
        }
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
        $definitions = array_map(array($this, 'createMapping'), $readDef->xpath("object"));
        return new ProtocolDefinition
        (
            $name, 
            $type,
            (string)$readDef['contenttype'],
            (string)$readDef['scope'],
            (string)$readDef->query,
            $definitions,
            $parser
        );
    }
}
