<?php
namespace Rexume\Application\Views;

class NavigationView extends StaticView
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->setBody("error/error.inc");
    }
}

