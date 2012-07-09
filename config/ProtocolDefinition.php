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
    protected $definitions;
    protected $parser = 'XMLSimpleParser';
    
    private $results;
    
    /**
     *
     * @param string $name the source
     * @param string $type the target 
     * @param string $contenttype The content type for the protocol
     * @param string $scope the scope to perform operations on
     * @param string $query the query fields to pass into the request
     * @param ProtocolMapping[] $definitions the mapping assocations related to the protocol
     * @param Parser $parser The parser to use for reading data contents
     */
    public function __construct($name, $type, $contenttype, $scope = null, $query = null, $definitions = null, $parser = null) {
        $this->type = $type;
        $this->name = $name;
        $this->scope = $scope;
        $this->query = $query;
        $this->contenttype = $contenttype;
        $this->definitions = $definitions;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        $this->results = array();
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
     * @return \Rexume\Models\Entity[] The results of the parse operation
     */
    public function parseOne(/*string*/ $data)
    {
        $parser_name = $this->parser;
        $callback = array($this, 'parseData');
        $parser = new $parser_name($data, $callback);
        $this->clearResults();
        $parser->beginParse();
        $results = $this->getResults();
        //return the first item in the list
        return (count($results) > 0) ? $results[0] : null;
    }
    
    public function parseData(\SimpleXMLIterator $content)
    {
        if($content)
        {
            $mapping = $this->getMappingBySource($content->getName());
            if($mapping)
            {
                $this->results[] = $mapping->parse($content);
            }
        }
    }
    
    public function clearResults()
    {
        $this->results = array();
    }
    
    /**
     * 
     * @return \Rexume\Models\Entity[] array of parsed result types
     */
    public function getResults()
    {
        return $this->results;
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
                        null
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
        $readprotocols = $protocol_xml->read;
        $result = array();
        //parse xml and create protocol and protocol mapping definitions
        foreach ($readprotocols as $readprotocol) {
            foreach($readprotocol->definition as $protocoldef){
                $protocol = $this->parseProtocol(
                        (string)$readprotocol['name'], 
                        (string)$readprotocol['type'], 
                        $protocoldef,
                        (string)$readprotocol['parser']
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
     * @param \SimpleXMLElement $protocoldef the definition xml
     * @param string $parser the name of the parser class to use
     * @return \Rexume\Configuration\ProtocolDefinition the created protocol defintion
     */
    public function parseProtocol($name, $type, $protocoldef, $parser)
    {
        $definitions = array_map(array($this, 'createMapping'), $protocoldef->xpath("mapping"));
        return new ProtocolDefinition
        (
            $name, 
            $type,
            (string)$protocoldef['contenttype'],
            (string)$protocoldef['scope'],
            (string)$protocoldef->query,
            $definitions,
            $parser 
        );
    }
}
