<?php
namespace Rexume\Application\Models\Enums;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass1
 *
 * @author sam.jr
 */
class FlagsEnum extends Enum {
    public function __construct( /*...*/ ) {
        $args = func_get_args();
        for( $i=0, $n=count($args), $f=0x1; $i<$n; $i++, $f *= 0x2 )
            $this->add($args[$i], $f);
    }
}

