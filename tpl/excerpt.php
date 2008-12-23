<?php
$class = array(
	'post hentry',
	'author-' . slugify($node->author),
	slugify($node->status)
);
foreach ($node->tags as $tag) {
	$class[] = 'category-' . slugify($tag);
}
$class = join(" ", $class);

$taglinks = array();
foreach ($node->tags as $tag => $tagname) {
	$taglinks[] = '<a href="' . urlify($tag) . '" rel="tag">' . $tagname . '</a>';
}

echo '<div class="'. $class .
	'" id="post-'. $node->slug .'">' . "\n" .
	'<h3><a href="'. $node->permalink .'" rel="bookmark" title="Permanent Link to '.
	htmlspecialchars($node->title, ENT_QUOTES) .'">'. $node->title . '</a></h3>' .
	'<small>' .date(Config::get('DayArchiveTitleDatePart'), $node->date) . '</small>' .
	'<div class="entry">' . $node->body . '</div>' .
	'<p class="postmetadata"><a href="' . $node->permalink . '#disqus_thread">View Comments</a> ' . 
		'Posted in ' . join(', ', $taglinks) . '</div>';
	// TODO: Put a comment link here to disqus?
	// TODO: Should maybe be a meta.php template?
	
