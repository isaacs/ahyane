<div id="hd">
	<h1><?php echo Config::get("SiteName"); ?></h1>
	<p><?php echo Config::get("SiteDescription"); ?></p>
	<?php
	if ($node->header("previous") || $node->header("next")) {
		echo '<div class="nav">';
		if ($node->header("previous")) {
			echo '<a class="previous" href="'. $node->header("previous")->href . '">' .
				$node->header("previous")->title . '</a>';
		}
		if ($node->header("previous") && $node->header("next")) {
			echo '<span>|</span>';
		}
		if ($node->header("next")) {
			echo '<a class="previous" href="'. $node->header("previous")->href . '">' .
				$node->header("previous")->title . '</a>';
		}
		echo '</div>';
	}
	?>
</div>
