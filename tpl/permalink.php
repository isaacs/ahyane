<div class="post" id="post-<?php echo str_replace('/', '-', $node->path) ?>">
	<div class="hd">
		<?php
		if ($node->tags) {
			$tags = array();
			foreach ($node->tags as $path => $tag) {
				$tags = '<a rel="tag" href="' . $node->root->__($path)->href . '">' .
					$tag . '</a>';
			}
			echo '<ul class="tags"><li>' . implode('</li><li>', $tags) . '</li></ul>';
		}
		
		// now the title of the post or page or whatever.
		echo '<h2>' . $node->header("title") . '</h2>';
		?>
	</div>
	<div class="bd">
		<?php echo $node->content->body; ?>
	</div>
	<?php
	$node->template("nav.php");
	$node->template("comment.php");
	?>
</div>