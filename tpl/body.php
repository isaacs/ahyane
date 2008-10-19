<div id="bd">
	<div id="yui-main">
		<div class="yui-b">
			<?php
			if ($node->archive) {
				$node->template("archive.php");
			} elseif ($node->permalink) {
				$node->template("permalink.php");
			}
			?>
		</div>
	</div>
	<?php
	$node->template("sidebar.php");
	?>
</div>