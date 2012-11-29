<?php
namespace Rexume\Application\Views;

class VerificationView extends StaticView
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->setBody("verify/verify.inc");
    }
}
