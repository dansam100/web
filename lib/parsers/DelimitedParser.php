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
    /**
     * 
     * @param ProtocolBind[] $mappings
     * @param string $delimiter
     */
    public function __construct($mappings, $delimiter = null) {
        $this->delimiter = $delimiter;
        $this->mappings = $mappings;
    }
    
    public function parse($content)
    {
        array_map("trim", explode($this->delimiter, $content));
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