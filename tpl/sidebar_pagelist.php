<ul>
	<?php
	foreach ($pages as $page) {
		echo "<li><a href=\"{$page['permalink']}\">{$page['title']}</a>";
		if (!empty($page['children'])) {
			$node->template("sidebar_pagelist.php", array("pages"=>$page['children']));
		}
		echo "</li>";
	}
	?>
</ul>