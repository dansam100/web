<?php
	namespace Rexume\Application\Views;
	
	class LoginView extends StaticView
	{
		public function __construct($controller, $action)
		{
			parent::__construct($controller, $action);
			$this->setBody("login/login.inc");
		}
	}
