<div class="archive" id="archive<?php echo str_replace('/', '-', $node->path) ?>">
	<div class="hd">
		<h2><?php echo $node->header("title"); ?></h2>
	</div>
	<div class="bd">
		<?php
		foreach ($node->content->body as $post) {
			?>
			<div class="post" id="<?php
				echo $post->headers->slug;
			?>">
				<h3><a rel="bookmark" href="<?php
					echo $post->headers->permalink;
				?>"><?php
					echo $post->headers->title;
				?></a></h3>
				<?php echo $post->excerpt . '<a rel="bookmark" class="more" href="' .
					$post->headers->permalink . '">' . sprintf(
						Config::get("ReadMoreText"), $post->headers->title
					) . '</a>';
				?>
			</div>
			<?php
			// echo "<pre>";
			// print_r($post->headers);
			// echo "</pre>";
		}
		?>
	</div>
	<?php $node->template("nav.php"); ?>
</div>
