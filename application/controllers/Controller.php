<?php
    interface IController
    {
    	function getModel();
		function getView();
    }
    
    class Controller implements IController
    {
    	private $model;
		private $view;
		private $action;
			
    	function __construct($model, $view, $action)
		{
			$this->action = $action;
			$this->view = new $view($this, $action);
			$this->model = new $model;
		}
		
		function __destruct()
		{
			$this->view->render();
		}
		
		function getView()
		{
			return $this->view;
		}
		
		function getModel()
		{
			return $this->model;
		}
    }
?>