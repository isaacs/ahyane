<div id="hd">
	<h1><?php echo Config::get("SiteName"); ?></h1>
	<p><?php echo Config::get("SiteDescription"); ?></p>
	<?php
	$node->template("nav.php");
	?>
</div>
