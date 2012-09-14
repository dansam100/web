<?php
namespace Rexume\Configuration;

class ProtocolObject
{
    private $name;
    private $type;
    private $protocol;
    /**
     * A list of name/value bindings
     * @var ProtocolBind[]
     */
    private $bindings;
    
    /**
     * The parent definition that houses the protocol object
     * @var ProtocolDefinition
     */
    protected $parent;
    
    /**
     *
     * @param string $name the source
     * @param string $type the target
     * @param ProtocolDefinition $parent The parent definition that houses the protocol object
     * @param ProtocolDefinition $protocol the read protocol associated with this binding
     * @param ProtocolBind[] $bindings the mapping associations related to the protocol
     */
    public function __construct($name, $type, $parent = null, $protocol = null, $bindings = array()) 
    {
        $this->name = $name;
        $this->type = $type;
        $this->parent = $parent;
        $this->protocol = $protocol;
        $this->bindings = array();
        foreach($bindings as $binding){
            $this->bindings[$binding->source()] = $binding;
        }
    }
    
    public function parent($parent = null)
    {
        if(!empty($parent))
        {
            $this->parent = $parent;
        }
        return $this->parent;
    }
    
    /**
     * Parses a given node element and returns the resulting object
     * @param SimpleXMLElement $content the contents to parse
     * @param IParser $callback A call back to find values within the current node. The callback must take two parameters: content (SimpleXMLElement) and a key (string)
     */
    public function parse($content, $callback)
    {
        //@var Entity resulting entity to return
        $result = new $this->type;
        //for the case where a protocol is defined in a mapping, do another read if necessary and parse the contents
        $protocol = $this->protocol();
        if(!empty($protocol)){
            //parse the query into tokens to find parameters and supply those parameters
            $tokens = getTokens($protocol->query(), '\${(*)}');
            $query = $protocol->createQueryFromTokens($protocol->query(), $this->parseValues($tokens, $content, $callback));
            //create a reader object and retrieve the contents
            $reader = new \Rexume\Readers\OAuthReader($protocol->name());
            $subcontent = $reader->read($protocol->scope(), $query);
            //parse the received contents and assign to the current object
            $subresult = $protocol->parseOne($subcontent);
            foreach($protocol->targets() as $target){
                //lhs has type Entity and rhs has type Entity
                $result->$target = $subresult->$target;
            }
        }
        //main parsing
        foreach($this->bindings as $binding){
            $output = null;
            $target = $binding->target();
            $mapping = $this->parent->getMappingByName($target);
            $value = $callback->parseValue($content, $binding->source());
            if(!empty($value)){
                if(isset($mapping)){
                    $output = $mapping->parse($value, $callback);
                }
                else{
                    $output = $binding->parse($value);
                }
                if(!empty($output)){
                    if(is_array($output)){   //treat arrays specially
                        if(is_array($result->$target)){   //add arrays entry by entry
                            foreach($output as $entry){
                                array_push($result->$target, $entry);
                            }
                        }
                        else{   //if the target does not expect an array and yet given one, use only the first entry
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
     * @param IParser $callback parser callback for intepreting node values
     */
    public function parseValues($sources, $content, $callback)
    {
        $result = array();
        foreach($sources as $source)
        {
            if(isset($callback)){
                $result[$source] = $callback->parseValue($content, $source);
            }
        }
        return $result;
    }
    
    /**
     * Gets the mapping source's value
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
    
    /**
     * Gets the mapping target's value
     * @return string 
     */
    public function type()
    {
        return $this->type;
    }
    
    /**
     * Returns the protocol definition for the given mapping
     * @return ProtocolDefinition
     */
    public function protocol()
    {
        return $this->protocol;
    }
}

?>
