<?php

if (
	$node->feed
)	$node->template('feed.php');
else {
	$node->template('htmlhead.php');
	$node->template('htmlbody.php');
}


