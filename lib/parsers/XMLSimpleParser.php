<?php
/**
 * Description of XMLSimpleParser
 *
 * @author sam.jr
 */
class XMLSimpleParser {
    /**
     *
     * @var string xml contents
     */
    private $xml_doc;
    /**
     *
     * @var SimpleXMLIterator
     */
    private $parser;
    /**
     *
     * @var callback call back to get the mapping related node
     */
    private $callback;
    /**
     *
     * @var mixed results of the parse operation
     */
    private $results;
    
    public function __construct($xml, $bind_callback = null) {
        $this->xml_doc = $xml;
        $this->callback = $bind_callback;
        $this->parser = new \SimpleXMLIterator($xml);
        $this->results = array();
    }
    
    public function parse()
    {
        //process the root node
        if(isset($this->callback))
        {
            $mapping = call_user_func($this->callback, $this->parser->getName());
            if(!empty($mapping))
            {
                $this->results[] = $this->invoke_parser($mapping, $this->parser);
            }
        }
        //process children
        $this->start($this->parser);
        return $this->results;
    }
    
    private function invoke_parser($mapping, \SimpleXMLElement $content)
    {
        $callback = array($this, 'getValue');
        $this->results[] = $mapping->parse($content, $callback);
    }
    
    private function start(\SimpleXMLIterator $node)
    {
        for($node->rewind(); $node->valid(); $node->next())
        {
            if(isset($this->callback))
            {
                $mapping = call_user_func($this->callback, $node->key());
                if(!empty($mapping))
                {
                    $this->results[] = $this->invoke_parser($mapping, $node->current());
                }
            }
            if($node->hasChildren())
            {
                $this->start($node->current());
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
