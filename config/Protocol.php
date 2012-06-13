<?php
namespace Rexume\Configuration;
require_once("ProtocolMapping.php");

/**
 * Description of Protocol
 *
 * @author sam.jr
 */
class Protocol {
    private $name;
    private $type;
    private $query;
    private $definitions;
    
    /**
     *
     * @param string $name the source
     * @param string $type the target 
     * @param ProtocolMapping[] $definitions the mapping assocations related to the protocol
     */
    public function __construct($name, $type, $query = null, $definitions = null) {
        $this->type = $type;
        $this->name = $name;
        $this->query = $query;
        $this->definitions = $definitions;
    }
    
    /**
     *
     * @param string $name
     * @return ProtocolMapping The resulting mapping 
     */
    public function getMappingBySource(string $name)
    {
        foreach($this->definitions as $definition)
        {
            if($definition->source == $name)
            {
                return $definition;
            }
        }
        return null;
    }
    
    public function getQuery()
    {
        return $this->query;
    }
}
