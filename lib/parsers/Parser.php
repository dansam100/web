<?php
namespace Rexume\Parsers;

interface IParser extends IValueParser{
    function getValue($content, $callback);
    function getValues($content, $key);
    function parse($content, $callback);
}

interface IValueParser{
    function getObject($key);
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
    
    public abstract function getValue($content, $key);
    public abstract function getValues($content, $key);
    public abstract function parse($content, $callback);
    public abstract function getObject($key);
}

?>
