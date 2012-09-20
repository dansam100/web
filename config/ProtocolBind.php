<?php
namespace Rexume\Configuration;
class ProtocolBind
{
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
     * @param string $parser
     * @param ProtocolBind[] $bindings
     */
    public function __construct($source, $target, $type = null, $default = null, $parser = null, $bindings = null) {
        $this->source = $source;
        $this->target = $target;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        if(!empty($type)){
            $this->type = $type;
        }
        if(!empty($default)){
            $this->default = $default;
        }
        $this->bindings = array();
        foreach($bindings as $binding){
            $this->bindings[$binding->source] = $binding;
        }
    }
    
    public function parse($content)
    {
        $result = null;
        if(!empty($content)){
            if(is_array($content)){
                $output = $content[0];
            }
            if(!empty($this->parser)){
                $parser = new $this->parser($this->bindings);
                $output = $parser->parse((string)$output);
                if(is_array($result)){
                    $result = array();
                    foreach($output as $item){
                        $new_obj = new $this->type();
                        $target = $this->target;
                        $new_obj->$target = $item;
                        $result[] = $new_obj;
                    }
                    return $result;
                }
                else{
                    $result = $output;
                }
            }
        }
        elseif(!empty($this->default)){
            $result = $this->default;
        }
        else{ $result = ""; }
        settype($result, $this->type);
        return $result;
    }
    
    public function source()
    {
        return $this->source;
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
