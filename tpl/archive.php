<div class="archive" id="archive<?php echo str_replace('/', '-', $node->path) ?>">
	<div class="hd">
		<h2><?php echo $node->title; ?></h2>
	</div>
	<div class="bd">
		<?php

		foreach (
			$node->body as $post
		) $post->template(
			"postbody.php", array(
				"show"=>"excerpt",
				"headertag" => "h3",
				"showmore" => true
			)
		);
		
		?>
	</div>
	<?php $node->template("nav.php"); ?>
</div>
