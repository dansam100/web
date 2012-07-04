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
     * @var callback
     */
    private $callback;
    public function __construct($xml, $element_callback = null) {
        $this->xml_doc = $xml;
        $this->parser = new SimpleXMLIterator($xml);
        $this->callback = $element_callback;
    }
    
    public function beginParse()
    {
        $this->start($this->parser);
    }
    
    public function start(\SimpleXMLIterator $node)
    {
        for($node->rewind(); $node->valid(); $node->next())
        {
            if(isset($this->callback))
            {
                call_user_func($this->callback, $node);
            }
            if($node->hasChildren())
            {
                $this->start($node);
            }
        }
    }
}

?>
