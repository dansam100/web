<?php
namespace Rexume\Application\Views;
/**
 * Description of DataView
 *
 * @author sam.jr
 */
class DataView extends StaticView {
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->clearContent();
        header("Content-type: text/xml");
    }
}
