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
     * @param ProtocolMapping[] $definitions the mapping associations related to the protocol
     * @param IParser $parser a parser to use for the interpreting the bind value
     */
    public function __construct($source, $target, $protocol, $bindings = null, $parser = null) {
        $this->source = $source;
        $this->target = $target;
        $this->parser = $parser;
        $this->protocol = $protocol;
        $this->bindings = $bindings;
        foreach ($this->bindings as $binding) {
            if(!isset($binding->source))
            {
                $binding->source = $this->source;
            }
            if(!isset($binding->target))
            {
                $binding->target = $this->target;
            }
        }
    }
    
    /**
     * Parses a given node element and returns the resulting object
     * @param SimpleXMLElement $content the contents to parse
     * @param callback $bind_callback A call back to find values within the current node. The callback must take two parameters: content (SimpleXMLElement) and a key (string)
     */
    public function parse($content, $bind_callback)
    {
        //@var Entity resulting entity to return
        $result = new $this->target;
        //for the case where a protocol is defined in a mapping, do another read if necessary and parse the contents
        $protocol = $this->getProtocol();
        if(!empty($protocol)){
            //parse the query into tokens to find parameters and supply those parameters
            $tokens = getTokens($protocol->getQuery(), '\${(*)}');
            $query = $protocol->createQueryFromTokens($protocol->getQuery(),
                    $this->parseValues($tokens, $content, $bind_callback));
            //create a reader object and retrieve the contents
            $reader = new \Rexume\Readers\OAuthReader($protocol->getName());
            $subcontent = $reader->read($protocol->getScope(), $query);
            //parse the received contents and assign to the current object
            $subresult = $protocol->parseOne($subcontent);
            foreach($protocol->getTargetBindings() as $target){
                //lhs has type Entity and rhs has type Entity
                $result->$target = $subresult->$target;
            }
        }
        foreach($this->bindings as $binding){
            $target = $binding->target;
            $value = call_user_func_array($bind_callback, array($content, $binding->source));
            if(!empty($value)){
                if(!empty($this->parser)){
                    $local_parser = new $this->parser($binding->bindings);
                    $output = $local_parser->parse($value);
                    //treat arrays specially
                    if(is_array($output)){
                        //add arrays entry by entry
                        if(is_array($result->$target)){
                            foreach($output as $entry){
                                array_push($result->$target, $entry);
                            }
                        }
                        //if the target does not expect an array and yet given one, use only the first entry
                        else{
                            $result->$target = $output[0];
                        }
                    }
                    else{
                        $result->$target = $output;
                    }
                }
                else{
                    $result->$target = $value;
                }
            }
        }
        return $result;
    }
    
    /**
     * Parses a list of supplied sources and returns their respective values in a keyed array
     * @param array $sources the source variables to match
     * @param mixed $content The content to match against
     * @param callback $bind_callback parser callback for intepreting node values
     */
    public function parseValues($sources, $content, $bind_callback)
    {
        $result = array();
        foreach($sources as $source)
        {
            if(is_callable($bind_callback)){
                $result[$source] = call_user_func_array($bind_callback, array($content, $source));
            }
        }
        return $result;
    }
    
    /**
     * Gets the mapping source's value
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Gets the mapping target's value
     * @return string 
     */
    public function getTarget()
    {
        return $this->target;
    }
    
    /**
     * Returns the protocol definition for the given mapping
     * @return ProtocolDefinition
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
}