<div class="archive" id="archive<?php echo str_replace('/', '-', $node->path) ?>">
	<div class="hd">
		<h2><?php echo $node->title; ?></h2>
	</div>
	<div class="bd">
		<?php

		foreach ($node->body as $post) {
			?>
			<div class="<?php
			$classes = array(
				'post hentry',
				'author-' . slugify($post->author),
				slugify($post->status),
				'y' . date('Y', $post->date),
				'm' . date('m', $post->date),
				'd' . date('d', $post->date),
				'h' . date('H', $post->date)
			);
			foreach ($post->tags as $tag) {
				$classes[] = 'tag-' . slugify($tag);
			}
			echo join(" ", $classes);
			?>" id="<?php
				echo $post->slug;
			?>">
				<?php
				if (!empty($post->tags)) {
					?>
					<ul class="tags">
						<?php
						foreach ($post->tags as $url => $tag) {
							echo "\n" . '<li><a rel="tag" href="' .
								urlify($url) . '">' . $tag . '</a></li>';
						}
						?>
					</ul>
					<?php
				}
				?>
				<h3><a rel="bookmark" href="<?php
					echo $post->permalink;
				?>"><?php
					echo $post->title;
				?></a></h3>
				<?php echo $post->excerpt . '<a rel="bookmark" class="more" href="' .
					$post->permalink . '">' . sprintf(
						Config::get("ReadMoreText"), $post->title
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
