<?php
namespace Rexume\Lib\Parsers;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompoundDelimitedParser
 *
 * @author sam.jr
 */
class CompoundDelimitedParser{
    public function __construct($mappings, $type, $delimiters = array(',', '\n')) {
        $delimiter = join("", $delimiters);
        parent::__construct($mappings, $type, $delimiter);
    }
}