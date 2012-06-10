<?php
	$email = "";	
	$controller = $this->getController();
	if(isset($_GET['email']))
	{
        $email = $_GET['email'];
	}
?>
<div id="body">
	<h1>Rexume</h1>
	<form action="/login/doLogin" method="post">
		<div id="row" class="error">
			<?php echo $controller->getError(); ?>
		</div>
		<div id="row">
			<input type="text" id="email" name="email" placeholder="Email Address" maxlength="256" value="<?php echo $email ?>"/>
		</div>
		<div id="row">
			<input type="password" id="password" name="password" placeholder="Password" maxlength="45" />
		</div>
		<div id="row">
			<a href="login/reminder">Forgot your password?</a>
			<input type="submit" value="Login"/>
		</div>
	</form>
</div>