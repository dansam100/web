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
    protected $type = 'string';
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
    public function __construct($source, $target, $type = null, $parser = null, $bindings = null) {
        $this->source = $source;
        $this->target = $target;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        if(!empty($type)){
            $this->type = $type;
        }
        $this->bindings = array();
        foreach($bindings as $binding){
            $this->bindings[$binding->source] = $binding;
        }
    }
    
    public function parse($content)
    {
        if(!empty($this->parser)){
            $parser = new $this->parser($this->bindings);
            return $parser->getValue($content);
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
