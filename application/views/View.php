<?php
    interface IView{
    	function getController();
		function getModel();
		function render();
    }
	
	class View implements IView
	{
		private $controller;
		private $model;
		private $action;
			
		function __construct($controller, $action)
		{
			$this->controller = $controller;
			$this->action = $action;
			$this->model = $controller->getModel();
		}
		
		function getController()
		{
			return $this->controller;
		}
		
		function getAction()
		{
			return $this->action;
		}
		
		function getModel()
		{
			return $this->model;
		}
		
		function render()
		{
			
		}
	}
