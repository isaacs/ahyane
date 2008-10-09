<div class="archive" id="archive<?php echo str_replace('/', '-', $node->path) ?>">
	<div class="hd">
		<h2><?php echo $node->header("title"); ?></h2>
	</div>
	<div class="bd">
		<?php
		foreach ($node->content->body as $post) {
			?>
			<div class="<?php
			$classes = array(
				'post hentry',
				'author-' . $node->slugify($post->headers->author),
				$node->slugify($post->headers->status),
				'y' . date('Y', $post->headers->date),
				'm' . date('m', $post->headers->date),
				'd' . date('d', $post->headers->date),
				'h' . date('H', $post->headers->date)
			);
			foreach ($post->headers->tags as $tag) {
				$classes[] = 'tag-' . $node->slugify($tag);
			}
			echo join(" ", $classes);
			?>" id="<?php
				echo $post->headers->slug;
			?>">
				<?php
				if (!empty($post->headers->tags)) {
					?>
					<ul class="tags">
						<?php
						foreach ($post->headers->tags as $url => $tag) {
							echo "\n" . '<li><a rel="tag" href="' .
								$node->urlify($url) . '">' . $tag . '</a></li>';
						}
						?>
					</ul>
					<?php
				}
				?>
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
