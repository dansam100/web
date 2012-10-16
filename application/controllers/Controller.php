<?php
namespace Rexume\Application\Controllers;

interface IController
{
    function getModel();
    function getView();
}

class Controller implements IController
{
    /**
    * The controller model
    * @var IModel
    */
    protected $model;
    /**
    * The controller view
    * @var IView
    */
    protected $view;
    /**
    * The default action of the controller
    * @var string
    */
    protected $action;
    
    /**
     * The error thrown by the controller
     * @var string
     */
    protected $error;

    function __construct($model, $view, $action)
    {
        $this->action = $action;
        if(!empty($model))
        {
            $this->model = new $model;
        }
        if(!empty($view))
        {
            $this->view = new $view($this, $action);
        }
    }

    function __destruct()
    {
        $this->view->render();
    }

    /**
        * The controller view
        * @return IView
        */
    function getView()
    {
        return $this->view;
    }

    /**
        * The controller model
        * @return IModel
        */
    function getModel()
    {
        return $this->model;
    }
}