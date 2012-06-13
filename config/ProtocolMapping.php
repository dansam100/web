<?php
namespace Rexume\Configuration;
use Rexume\Parsers;

/**
 * Mapping.php
 * Mapping class for each binding
 * @author sam.jr 
 */
class ProtocolMapping{
    private $source;
    private $target;
    private $parser;
    private $bindings;
    
    /**
     *
     * @param string $source the source
     * @param string $target the target 
     * @param Mapping[] $definitions the mapping assocations related to the protocol
     */
    public function __construct($source, $target, $bindings = null, $parser = null) {
        $this->source = $source;
        $this->target = $target;
        $this->parser = $parser;
        $this->bindings = $bindings;
    }
    
    /**
     * Parses a given node element and returns the resulting object
     * @param SimpleXMLElement $content the contents to parse
     */
    public function parse(\SimpleXMLElement $content)
    {
        $result = new $target;
        foreach($this->bindings as $binding){
            $target = $binding->target;
            $value =  $content->xpath($binding->source);
            if(isset($value)){
                if(isset($this->parser)){
                    $parser = new $parser($binding->bindings);
                    $result->$target = $parser->parse($value[0]);
                }
                else{
                    $result->$target = $value[0];
                }
            }
        }
        return $result;
    }
}
