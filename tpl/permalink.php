<div class="post" id="<?php echo $node->slug ?>">
	<div class="hd">
		<?php
		$tags = array();
		foreach ($node->tags as $path => $tag) {
			$tags[] = '<a rel="tag" href="' . urlify($path) . '">' .
				$tag . '</a>';
		}
		if (!empty($tags)) {
			echo '<ul class="tags"><li>' . implode('</li><li>', $tags) . '</li></ul>';
		}
		
		// now the title of the post or page or whatever.
		echo '<h2>' . $node->title . '</h2>';
		
		echo '<ul class="meta">' .
			( $node->date
				? '<li class="published"><span name="value" style="display:none">' .
					$node->date . '</span>' .
					date('r', $node->date) . '</li>'
				: ''
			) .
			'<li class="author">By ' . $node->author . '</li>' . 
			'</ul>';
		?>
	</div>
	<div class="bd">
		<?php echo $node->body; ?>
	</div>
	<?php
	$node->template("nav.php");
	$node->template("comment.php");
	?>
</div>