<?php
class TW_ArchiveTitle extends TW_Base {
	protected static function each ($node) {
		if ($node->title) return;

		// it's a post or static page without a title.
		// that's very strange, but not completely impossible.
		if (
			!$node->archive
		) return $node->title = Config::get("DefaultTitle");

		// most likely case.  Node is an archive of some sort.
		$type = $node->archivetype;
		if (
			$type === "tag"
		) return $node->setHeader("title", sprintf(
			Config::get("TagArchiveTitle"), $node->tag
		));

		$node->setHeader("title", sprintf(
			Config::get("${type}ArchiveTitle"), date(
				Config::get("${type}ArchiveTitleDatePart"), $node->archivestart
			)
		));
	}
	public static function walk ($node) { parent::walk($node); }
}



