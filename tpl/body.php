<div id="bd">
	<div id="yui-main">
		<div class="yui-b">
			<?php
			if ($node->header("archive")) {
				$node->template("archive.php");
			} elseif ($node->header("permalink")) {
				$node->template("permalink.php");
			}
			?>
		</div>
	</div>
	<?php
	$node->template("sidebar.php");
	?>
</div>