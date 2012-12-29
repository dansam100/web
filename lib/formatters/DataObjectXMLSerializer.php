<?php
namespace Rexume\Lib\Formatters;
/**
 * Description of DataObjectXMLSerializer
 *
 * @author sam.jr
 */
class DataObjectXMLSerializer {
    protected $objects;
    
    public function __construct($objects) {
        $this->objects = $objects;
    }
    
    public function format($object = null){
        if(isset($object)){
            return $this->encodeObj($object);
        }
        else{
            return $this->encodeObj($this->objects);
        }
    }
    
	/**
	 *    Encode an object as XML string
	 *    @param        \Rexume\Config\DataObject $obj
	 *    @param        string $root_node
	 *    @return        string $xml
	 */
	public function encodeObj($obj, $root_node = 'response') {
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml .= self::encode($obj, $root_node, $depth = 0);
		return $xml;
	}


	/**
	 *    Encode an object as XML string
	 *    @param        Rexume\Config\DataObject $data
	 *    @param        string $node        
	 *    @param        int $depth Used for indentation
	 *    @return        string $xml
	 */
	private function encode($data, $node, $depth) {
        $attributes = ""; $subelements = "";
        if(is_array($data)){
            foreach($data as $key => $val) {
               $subelements .= $this->encode($val, strtolower($val->class), ($depth + 1)); 
            }
        }
        elseif($data instanceof \Rexume\Config\DataObject) {
            foreach($data as $key => $val) {
                if(!$data->isHidden($key)){
                    if(is_array($val)){
                        if($data->isCollapsed($key)){
                            foreach($val as $subVal){
                                $subelements .= $this->encode($subVal, strtolower($subVal->class), ($depth + 1));
                            }
                        }
                        else{
                            $subelements .= $this->encode($val, $key, ($depth + 1));
                        }
                    }
                    elseif(is_object($val)){
                        if($data->isAttribute($key)){
                            $attributes .= to_key_value_pair($val, true);
                        }
                        else{
                            $subelements .= $this->encode($val, strtolower($val->class), ($depth + 1));
                        }
                    }
                    else{
                        if($data->isAttribute($key)){
                            $attributes .= "$key='" . htmlspecialchars($val) . "' ";
                        }
                        else{
                            $subelements .= str_repeat("\t", ($depth + 1));
                            $subelements .= "<$key>" . htmlspecialchars($val) . "</$key>\n";
                        }
                    }
                }
            }
            if(strcmp(\trim($subelements), "") == 0){
                $result = "<$node " . \trim($attributes) . "/>\n";
            }
            else{
                $result = "<$node " . \trim($attributes) . ">\n" . $subelements . "\n</$node>";
            }
            return $result;
        }
        else{
            $subelements .= str_repeat("\t", ($depth + 1));
            $subelements .= "<$key>" . htmlspecialchars($val) . "</$key>\n";
        }
        $result = "<$node>\n" . $subelements . "\n</$node>";
        return $result;
	}
}
