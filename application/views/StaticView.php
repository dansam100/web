<?php
    namespace Rexume\Views;
    
    interface IView{
    	function getController();
		function getModel();
		function render();
    }
    
    interface IStaticView{
		function getBody();
		function setBody($body);
		function getHeader();
		function setHeader($header);
		function getFooter();
		function setFooter($footer);
    }
	
	class StaticView implements IStaticView, IView
	{
		protected $controller;
		protected $model;
		protected $action;
		
		private $header = "includes/header.inc";
		private $footer = "includes/footer.inc";
		private $body;
		private $sidebar;
			
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
			if(isset($this->header)){ include($this->header); }
			if(isset($this->sidebar)){ include($this->sidebar); }
			if(isset($this->body)){ include($this->body); }
			if(isset($this->footer)){ include($this->footer); }
		}
		
		function getFooter()
		{
			return $footer;
		}
		
		function setFooter($footer)
		{
			$this->footer = $footer;
		}
		
		function getSidebar()
		{
			return $this->footer;
		}
		
		function setSidebar($sidebar)
		{
			$this->sidebar = $sidebar;
		}
		
		function getHeader()
		{
			return $this->header;
		}
		
		function setHeader($header)
		{
			$this->header = $header;
		}
		
		function getBody()
		{
			return $this->body;
		}
		
		function setBody($body)
		{
			$this->body = $body;
		}
	}
