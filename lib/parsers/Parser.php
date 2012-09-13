<?php
namespace Rexume\Parsers;

interface IParser extends IValueParser{
    function parseValue($content, $key);
    function parse($content, $callback);
    function getValue($key);
}

interface IValueParser{
    function getValue($key);
}


/**
 * Description of Parser
 *
 * @author sam.jr
 */
abstract class Parser implements IParser
{
    /**
     *
     * @var ProtocolObject[] 
     */
    protected $mappings;
    /**
     * Ctor
     * @param ProtocolObject[] $mappings 
     */
    public function __construct($mappings) {
        $this->mappings = $mappings;
    }
    
    public abstract function parseValue($content, $key);
    public abstract function parse($content, $callback);
    public abstract function getValue($key);
}

?>
