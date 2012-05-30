<?php
	namespace Rexume\Controllers;

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
			
    	function __construct($model, $view, $action)
		{
			$this->action = $action;
			$this->model = new $model;
			$this->view = new $view($this, $action);
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
?>