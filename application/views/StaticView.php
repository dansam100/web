<?php
    namespace Rexume\Application\Views;
    
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
		protected $action;
		
		private $header = "includes/header.inc";
		private $footer = "includes/footer.inc";
		private $body;
		private $sidebar;
			
		function __construct($controller, $action)
		{
			$this->controller = $controller;
			$this->action = $action;
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
			return $this->controller->getModel();
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
			return $this->footer;
		}
		
		function setFooter($footer)
		{
			$this->footer = $footer;
		}
		
		function getSidebar()
		{
			return $this->sidebar;
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
        
        function clearContent()
        {
            $this->body = null;
            $this->header = null;
            $this->footer = null;
            $this->sidebar = null;
        }
	}
