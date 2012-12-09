<?php
namespace Rexume\Application\Views;
/**
 * Description of DataView
 *
 * @author sam.jr
 */
class XMLDataView extends StaticView {
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->clearContent();
        header("Content-type: text/xml");
    }
    
    public function render()
    {
        $model = $this->getModel();
        $formatter = new \Rexume\Lib\Formatters\SimpleXMLFormatter($model->objects());
        echo $formatter->format();
    }
}
