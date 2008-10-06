<?php

if ($node->header("feed")) {
	template('feed.php');
	return;
}

template('htmlhead.php');

template('htmlbody.php');



