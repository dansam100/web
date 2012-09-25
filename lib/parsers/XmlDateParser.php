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
    
    public function parse($content, $callback)
    {
        foreach($this->mappings as $mapping)
        {
            $bind = $mapping->target();
            $value = (string)$callback->parseValue($content, $mapping->source());
            if(isset($value)){
                $this->$bind = (int)$value;
            }
        }
        return date(DATE_ATOM, mktime(0,0,0,$this->month, $this->day, $this->year));
    }
}
