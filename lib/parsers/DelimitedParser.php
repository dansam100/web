<?php
namespace Rexume\Parsers;
/**
 * Description of DelimitedParser
 *
 * @author sam.jr
 */
class DelimitedParser
{
    protected $delimiter;
    protected $mappings;
    protected $content;
    /**
     * 
     * @param ProtocolBind[] $mappings
     * @param string $delimiter
     */
    public function __construct($mappings, $delimiter = null) {
        $this->delimiter = $delimiter;
        $this->mappings = $mappings;
        $this->content = array();
    }
    
    public function parse($content, $callback)
    {
        $result = array();
        foreach($this->mappings as $mapping){
            $target = $mapping->target();
            $value = $callback->getValue($content, $mapping->target());
            $this->$target = cast($value, $mapping->type());
            $result[] = array_map("trim", explode($this->delimiter, $this->$target));
        }
        return $result;
    }
}

class CommaDelimitedParser extends DelimitedParser{
    public function __construct(array $mappings, $delimiter = ',') {
        parent::__construct($mappings, $delimiter);
    }
}

class NewlineDelimitedParser extends DelimitedParser{
    public function __construct(array $mappings, $delimiter = "\n") {
        parent::__construct($mappings, $delimiter);
    }
}