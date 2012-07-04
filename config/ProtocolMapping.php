<?php
namespace Rexume\Configuration;
use Rexume\Parsers;
require_once("ProtocolDefinition.php");

/**
 * ProtocolMapping.php
 * ProtocolMapping class for each binding
 * @author sam.jr 
 */
class ProtocolMapping{
    private $source;
    private $target;
    private $parser;
    private $protocol;
    private $bindings;
    
    /**
     *
     * @param string $source the source
     * @param string $target the target
     * @param ProtocolDefinition $protocol the read protocol associated with this binding
     * @param ProtocolMapping[] $definitions the mapping assocations related to the protocol
     * @param IParser $parser a parser to use for the interpreting the bind value
     */
    public function __construct($source, $target, $protocol, $bindings = null, $parser = null) {
        $this->source = $source;
        $this->target = $target;
        $this->parser = $parser;
        $this->protocol = $protocol;
        $this->bindings = $bindings;
        foreach ($this->bindings as $binding) {
            if(!isset($binding->source)){
                $binding->source = $this->source;
            }
            if(!isset($binding->target)){
                $binding->target = $this->target;
            }
        }
    }
    
    /**
     * Parses a given node element and returns the resulting object
     * @param SimpleXMLElement $content the contents to parse
     */
    public function parse(\SimpleXMLElement $content)
    {
        $result = new $this->target;
        foreach($this->bindings as $binding){
            $target = $binding->target;
            $value =  $content->xpath($binding->source);
            if(isset($value)){
                if(isset($this->parser)){
                    $parser = new $this->parser($binding->bindings);
                    $result->$target = $parser->parse($value[0]);
                }
                else{
                    $result->$target = $value[0];
                }
            }
        }
        return $result;
    }
    
    public function getSource()
    {
        return $this->source;
    }
    
    public function getTarget()
    {
        return $this->target;
    }
    
    public function getProtocol()
    {
        return $this->protocol;
    }
}