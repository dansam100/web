<?php
namespace Rexume\Parsers;

/**
 * Description of DelimitedParser
 *
 * @author sam.jr
 */
class DelimitedParser
{
    private $delimiter;
    private $mappings;
    public function __construct($mappings, \string $delimiter = null) {
        $this->delimiter = $delimiter;
        $this->mappings = $mappings;
    }
    
    public function parse(\string $content)
    {
        array_map("trim", explode($this->delimiter, $content));
    }
}

class CommaDelimitedParser{
    public function __construct(array $mappings, $delimiter = ',') {
        parent::__construct($mappings, $delimiter);
    }
}

class NewlineDelimitedParser{
    public function __construct(array $mappings, $delimiter = "\n") {
        parent::__construct($mappings, $delimiter);
    }
}