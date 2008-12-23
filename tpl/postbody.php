<?php

$class = array(
	'post hentry',
	'author-' . slugify($node->author),
	slugify($node->status),
	'y' . date('Y', $node->date),
	'm' . date('m', $node->date),
	'd' . date('d', $node->date),
	'h' . date('H', $node->date)
);
foreach ($node->tags as $tag) {
	$class[] = 'tag-' . slugify($tag);
}
$class = join(" ", $class);

if (!isset($show)) $show = "body";
if (!isset($headertag)) $headertag = "h2";
if (!isset($showmore)) $showmore = false;

echo '<div class="' . $class . '" id="' . $node->slug . '">';
	if (!empty($node->tags)) {
		echo '<ul class="tags">';
			foreach ($node->tags as $url => $tag) {
				echo "\n" . '<li><a rel="tag" href="' .
					urlify($url) . '">' . $tag . '</a></li>';
			}
		echo '</ul>';
	}
	echo 
		"<$headertag>" .
			'<a rel="bookmark" href="' . $node->permalink . '">' . $node->title . '</a>' .
		"</$headertag>" .
		'<div class="bd">' . 
			$node->$show .
			($showmore
				? '<a rel="bookmark" class="more" href="' . $node->permalink . '">' . sprintf(
					Config::get("ReadMoreText"), $node->title
				) . '</a>'
				: ''
			) .
			'<a href="' . $node->permalink . '#disqus_thread">View Comments</a>' .
		'</div>';
		
echo '</div>';
