<?php
	$error = $this->getController()->getModel()->error();
?>
<div id="body">
	<h1>Rexume</h1>
	<div id="row" class="error">
        <?php echo $error; ?>
    </div>
</div>