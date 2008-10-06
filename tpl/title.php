<?php

// why in here at all?
if ($node->header("title")) return;

// it's a post or static page without a title.
// that's very strange, but not completely impossible.
if (
	!$node->header("archive")
) return $node->content->headers->title = Config::get("DefaultTitle");

// most likely case.  Node is an archive of some sort.
$type = $node->header("archivetype");
if (
	$type === "tag"
) return $node->content->headers->title = sprintf(Config::get("TagTitle"), $node->header("tag"));

$node->content->headers->title = sprintf(
	Config::get("${type}ArchiveTitle"), date(
		Config::get("${type}ArchiveTitleDatePart"), $node->header("archivestart")
	)
);

