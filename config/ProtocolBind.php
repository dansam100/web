<?php
namespace Rexume\Configuration;
class ProtocolBind
{
    protected $name;
    protected $source;
    protected $target;
    /**
     *
     * @var IValueParser $parser 
     */
    protected $parser;
    protected $type = 'object';
    protected $default;
    /**
     *
     * @var ProtocolBind[]
     */
    protected $bindings;
    
    /**
     * Ctor
     * @param string $source
     * @param string $target
     * @param string $type
     * @param string $name Name of the bind. Defaults to $source when not specified
     * @param string $parser
     * @param ProtocolBind[] $bindings
     */
    public function __construct($source, $target, $type = null, $name = null, $default = null, $parser = null, $bindings = null) {
        $this->source = $source;
        $this->target = $target;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        if(!empty($type)){
            $this->type = $type;
        }
        if(empty($name)){
            $this->name = $source;
        }
        else $this->name = $name;
        if(!empty($default)){
            $this->default = $default;
        }
        $this->bindings = array();
        foreach($bindings as $binding){
            $this->bindings[$binding->source] = $binding;
        }
    }
    
    public function parse($content, $callback)
    {
        $result = null;
        //print_r("type of ");
        //print_r((string)$content[0]);
        //print_r(" is " . gettype($content[0]));
        if(isset($content)){
            if(!empty($this->parser)){
                $parser = new $this->parser($this->bindings());   //create a new parser with the given bindings
                $output = $parser->parse($content, $callback);     //pass the contents through the parser
                if(is_collection($output)){
                    $result = array();
                    foreach($output as $item){
                        $new_obj = new $this->type();
                        $target = $this->target();
                        //assign the target value
                        $new_obj->$target = $item;
                        $result[] = $new_obj;
                    }
                    return $result;
                }
                else{
                    $result = $output;
                }
            }
            elseif(is_array($content)){
                $result = cast($content[0], $this->type());
            }
            else{
                $result = cast($content, $this->type());
            }
        }
        elseif(!empty($this->default)){
            $result = $this->default;
        }
        return $result;
    }
    
    public function name()
    {
        return $this->name;
    }
    
    public function source()
    {
        return $this->source;
    }
    
    public function type()
    {
        return $this->type;
    }
    
    public function target()
    {
        return $this->target;
    }
    
    public function bindings()
    {
        return array_values($this->bindings);
    }
}
