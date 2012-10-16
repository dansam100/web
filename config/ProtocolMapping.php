<?php
namespace Rexume\Config;

class ProtocolMapping
{
    protected $name;
    protected $type = 'string';
    protected $protocol;
    /**
     * A list of name/value bindings
     * @var ProtocolBind[]
     */
    protected $bindings;
    
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
        if(isset($protocol)){
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
            $target = $binding->target();
            //process protocol objects
            if(!empty($this->parent)){
                $mapping = $this->parent->getMappingByName($binding->name());
                $values = $callback->getValues($content, $binding->source());
                if(isset($mapping) && !empty($values)){
                    $output = array();
                    foreach($values as $value){
                        $output[] = $mapping->parse($value, $callback);    //protocolmapping returns an object
                    }
                }
                else{
                    //allow the binding to perform any extra parsing
                    $output = $binding->parse($values, $callback);
                }
            }
            else{
                //process protocolmappings
                if(is_collection($content)){
                    $content = $content[0];
                }
                $values = $callback->getValue($content, $binding->source());
                $output = $binding->parse($values, $callback);
            }
            if(isset($output)){
                if(is_collection($result->$target)){   //add arrays entry by entry
                    if(is_collection($output)){
                        foreach($output as $entry){
                            collection_add($result->$target, $entry);
                        }
                    }
                    else{
                        collection_add($result->$target, $output);
                    }
                }
                elseif(is_collection($output) && !empty($output)){     //if the target does not expect an array and yet given one, use only the first entry
                    $result->$target = $output[0];
                }
                else{
                    $result->$target = $output;
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
                $result[$source] = $callback->getValues($content, $source);
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
    
    public function bindings()
    {
        return array_values($this->bindings);
    }
}

?>
