<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en" id="document<?php echo str_replace("/", '-', $node->path); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="stylesheet" type="text/css" href="${TemplateURL}/style.css">
	<meta name="generator" content="Ahyane">
	
	<title>${SiteName} » <?php
	echo $node->home ? Config::get("SiteDescription") : $node->title;
	?></title>
	
</head>
