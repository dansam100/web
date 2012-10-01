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
    protected $type;
    private $result;
    /**
     * 
     * @param ProtocolBind[] $mappings
     * @param string $delimiter
     */
    public function __construct($mappings, $type, $delimiter = null) {
        $this->delimiter = $delimiter;
        $this->mappings = $mappings;
        $this->type = $type;
        $this->result = array();
    }
    
    public function parse($content, $callback)
    {
        foreach($this->mappings as $mapping){
            $target = $mapping->target();
            $value = $callback->getValue($content, $mapping->source());
            $results = explode($this->delimiter, $mapping->parse($value, $callback));
            foreach($results as $result){
                $item = new $this->type;
                $item->$target = $result;
                $this->results[] = $item;
            }
        }
        return $this->results;
    }
}

class CommaDelimitedParser extends DelimitedParser{
    public function __construct($mappings, $type, $delimiter = ',') {
        parent::__construct($mappings, $type, $delimiter);
    }
}

class NewlineDelimitedParser extends DelimitedParser{
    public function __construct($mappings, $type, $delimiter = "\n") {
        parent::__construct($mappings, $type, $delimiter);
    }
}