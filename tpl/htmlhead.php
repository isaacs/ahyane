<?php

if (!$node->header("title")) {
	template("title.php");
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en" id="<?php echo str_replace("/", '-', $node->path); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php
	echo Config::get("sitename") . ' » ' . (
		$node->header("home") ? Config::get("SiteDescription") : $node->header("title")
	);
	?></title>
</head>
