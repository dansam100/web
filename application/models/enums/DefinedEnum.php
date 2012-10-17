<?php
namespace Rexume\Application\Models\Enums;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author sam.jr
 */
class DefinedEnum extends Enum {
    public function __construct( /*array*/ $itms ) {
        foreach( $itms as $name => $enum )
            $this->add($name, $enum);
    }
}
