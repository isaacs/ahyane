<?php

if ($node->header("feed")) {
	$node->template('feed.php');
	return;
}

$node->template('htmlhead.php');

$node->template('htmlbody.php');



