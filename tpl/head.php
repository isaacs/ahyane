<div id="hd">
	<h1><a href="<?php
		echo Config::get("SiteURL");
	?>" rel="home"><?php
		echo Config::get("SiteName");
	?></a></h1>
	<p><?php echo Config::get("SiteDescription"); ?></p>
	<?php
	$node->template("nav.php");
	?>
</div>
