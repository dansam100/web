<?php
namespace Rexume\Configuration;
class ProtocolBind
{
    private $source;
    private $target;
    /**
     *
     * @var IValueParser $parser 
     */
    private $parser;
    private $bindings;
    
    /**
     * Ctor
     * @param string $source
     * @param string $target
     * @param IValueParser $parser
     * @param ProtocolBind[] $bindings
     */
    public function __construct($source, $target, $parser = null, $bindings = array()) {
        $this->source = $source;
        $this->target = $target;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        $this->bindings = array();
        if(!empty($bindings)){
            foreach($bindings as $binding){
                $this->bindings[$binding->source] = $binding;
            }
        }
    }
    
    public function parse($content)
    {
        if(!empty($this->parser)){
            $parser = $this->parser($binding->bindings());
            $parser->getValue($content);
        }
        else{
            return $content;
        }
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
