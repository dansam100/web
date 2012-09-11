<?php
/**
 * Description of XMLSimpleParser
 *
 * @author sam.jr
 */
class XMLSimpleParser {
    /**
     *
     * @var ProtocolObject[] 
     */
    private $mappings;
    /**
     *
     * @var mixed results of the parse operation
     */
    private $results;
    /**
     * Ctor
     * @param ProtocolObject[] $mappings 
     */
    public function __construct($mappings) {
        $this->mappings = $mappings;
        $this->results = array();
    }
    
    /**
     * 
     * @param \SimpleXMLElement $data
     * @param type $callback
     * @return type
     */
    public function parse(\SimpleXMLElement $data, $callback)
    {
        $callback = $callback;
        $parser = new \SimpleXMLIterator($data);
        //process the root node
        if(isset($callback))
        {
            $mappingObject = null;
            foreach($this->mappings as $mapping)
            {
                if(strcmp($mapping->name(), $parser->getName()) == 0){
                    $mappingObject = $mapping;
                    break;
                }
            }
            if(!empty($mappingObject))
            {
                $this->results[] = $this->invokeParser($mappingObject, $parser);
            }
        }
        //process children
        $this->start($parser, $callback);
        return $this->results;
    }
    
    /**
     * 
     * @param type $mapping
     * @param \SimpleXMLElement $content
     */
    private function invokeParser($mapping, \SimpleXMLElement $content)
    {
        $callback = array($this, 'getValue');
        $this->results[] = $mapping->parse($content, $callback);
    }
    
    /**
     * 
     * @param \SimpleXMLIterator $node
     * @param type $callback
     */
    private function start(\SimpleXMLIterator $node, $callback)
    {
        for($node->rewind(); $node->valid(); $node->next())
        {
            if(isset($callback))
            {
                $mapping = call_user_func($callback, $node->key());
                if(!empty($mapping))
                {
                    $this->results[] = $this->invokeParser($mapping, $node->current());
                }
            }
            if($node->hasChildren())
            {
                $this->start($node->current(), $callback);
            }
        }
    }
    
    /**
     * Get the value of the given source binding from the content xml
     * @param \SimpleXMLElement $content the xml to get the value from
     * @param string $source the binding target name
     * @return string results of the bind
     */
    public function getValue(\SimpleXMLElement $content, $source)
    {
        $result =  $content->xpath($source);
        if(!empty($result)){
            return (string)$result[0];
        }
        return null;
    }
}

?>
