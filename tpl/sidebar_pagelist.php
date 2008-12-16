<ul>
	<?php
	foreach ($pages as $page) {
		echo "<li><a href=\"{$page[3]}\">{$page[2]}</a>";
		if (!empty($page[4])) {
			$node->template("sidebar_pagelist.php", array("pages"=>$page[4]));
		}
		echo "</li>";
	}
	?>
</ul>