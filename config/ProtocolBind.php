<?php
namespace Rexume\Configuration;
class ProtocolBind
{
    private $source;
    private $target;
    private $parser;
    private $bindings;
    
    public function __construct($source, $target, $parser = null, $bindings = null) {
        $this->source = $source;
        $this->target = $target;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        $this->bindings = $bindings;
    }
    
    public function parse(\SimpleXMLElement $content)
    {
        
    }
}
