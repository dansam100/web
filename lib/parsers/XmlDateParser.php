<?php
namespace Rexume\Parsers;
/**
 * Date parser for xml structure.
 * 
 * <date>
 *  <month></month>
 *  <year></year>
 *  <day></day>
 * </date>
 *
 * @author sam.jr
 */
class XmlDateParser {
    /**
     *
     * @var ProtocolBind[]
     */
    private $mappings;
    private $year;
    private $month;
    private $day;
    
    /**
     * Ctor
     * @param ProtocolBind[] $mappings
     */
    public function __construct($mappings) {
        $this->year = 1970;
        $this->month = 1;
        $this->day = 1;
        $this->mappings = $mappings;
    }
    
    /**
     * 
     * @param \SimpleXMLElement[] $content
     * @param IParser $callback
     * @return date
     */
    public function parse($content, $callback)
    {
        foreach($this->mappings as $mapping)
        {
            $target = $mapping->target();
            $source = $mapping->source();
            foreach($content as $value){
                $result = cast($value->$source, $mapping->type());
                if(!empty($result)){
                    $this->$target = cast($value->$source, $result);
                }
                //$this->$target = cast($callback->parseValue($value, $mapping->source()), $mapping->type());
            }
        }
        return date(DATE_ATOM, mktime(0,0,0,$this->month, $this->day, $this->year));
    }
}
