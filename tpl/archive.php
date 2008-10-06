<div class="archive" id="<?php echo str_replace('/', '-', $node->path) ?>">
	<div class="hd">
		<h2><?php echo $node->header("title"); ?></h2>
	</div>
	<div class="bd">
		<?php
		foreach ($node->content->body as $post) {
			?>
			<div class="post" id="<?php
				echo str_replace('/', '-', $post->headers->permalink);
			?>">
				<h3><a rel="bookmark" href="<?php
					echo $post->headers->permalink;
				?>"><?php
					echo $post->headers->title;
				?></a></h3>
				<p><?php echo $post->excerpt; ?></p>
			</div>
			<?php
			echo "<pre>";
			// print_r($post->headers);
			echo "</pre>";
		}
		?>
	</div>
	<?php $node->template("nav.php"); ?>
</div>
