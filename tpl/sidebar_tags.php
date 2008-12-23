<ul>
	<?php
	foreach ($tags as $tag) {
		unset($tag['children']);
		echo "<li><a href=\"{$tag['href']}\">" .
			$tag['tag'] . "</a> ({$tag['postcount']})</li>";
	}
	?>
</ul>