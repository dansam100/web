<?php
namespace Rexume\Application\Controllers;

class NavigationController extends Controller
{
    public function pageNotFound()
    {
        $this->model->error("Page not found");
    }
    
    public function accessDenied()
    {
        
    }
}

