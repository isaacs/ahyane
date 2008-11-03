<?php

foreach (
	$node->body as $post
) $post->template("excerpt.php");

$node->template("nav.php");

