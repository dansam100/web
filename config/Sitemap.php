<?php
namespace Rexume\Configuration;
/*
    * A site map holds the controller, model and view information for a given url on the site
    */
class SiteMap
{
    private $controller;
    private $model;
    private $view;
    private $defaultAction;	
    private $isDefault;

    public function __construct($controller, $model = null, $view = null, $defaultAction = null, $isDefault = false)
    {
        $this->controller = $controller;
        $this->model = $model;
        $this->view = $view;
        $this->defaultAction = $defaultAction;
        $this->isDefault = $isDefault;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getDefaultAction()
    {
        return $this->defaultAction;
    }

    public function isDefault()
    {
        return $this->isDefault;
    }
}