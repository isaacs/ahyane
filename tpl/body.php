<?php

$wide = ($node->permalink && $node->type === "blog");

echo '<div id="content" class="' . 
	($wide ? 'widecolumn' : 'narrowcolumn') . '">';

if (
	!$node->home || $node->statichome
) echo '<h2 class="pagetitle">' . $node->title . '</h2>';

$node->template("nav.php");

$node->template(
	$node->archive
	? "archive.php"
	: "permalink.php"
);
echo '</div>';

if (!$wide) $node->template("sidebar.php");



