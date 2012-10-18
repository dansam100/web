<?php
namespace Rexume\Lib\Parsers;
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
    private $regex;
    /**
     * 
     * @param ProtocolBind[] $mappings
     * @param string $delimiter
     */
    public function __construct($mappings, $type, $delimiter = null) {
        $this->delimiter = sprintf("/[%s]+/", $delimiter);
        $this->mappings = $mappings;
        $this->type = $type;
        $this->result = array();
    }
    
    public function parse($content, $callback)
    {
        foreach($this->mappings as $mapping){
            $target = $mapping->target();
            $value = $callback->getValue($content, $mapping->source());
            $splits = preg_split($this->delimiter, $mapping->parse($value, $callback), PREG_SPLIT_NO_EMPTY);
            $results = array_map("trim", $splits);
            foreach($results as $result){
                //call 'new' for non-scalar types and create the instances
                if(!is_scalar_type($this->type)){
                    $item = new $this->type;
                    $item->$target = $result;
                    $this->results[] = $item;
                }
                else{   //return an array of objects for the scalar types
                    $this->results[] = cast($result, $this->type);
                }
            }
        }
        return $this->results;
    }
}