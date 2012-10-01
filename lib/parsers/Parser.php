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
     *
     * @var string 
     */
    protected $type;
    /**
     * Ctor
     * @param ProtocolObject[] $mappings 
     */
    public function __construct($mappings, $type) {
        $this->mappings = $mappings;
        $this->type = $type;
    }
    
    public abstract function getValue($content, $key);
    public abstract function getValues($content, $key);
    public abstract function parse($content, $callback);
    public abstract function getObject($key);
}

?>
