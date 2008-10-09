<?php

class TW_AddIndexFile extends TW_Base {
	protected static function each ($node) {
		if (
			!$node->content || !$node->content->body
		) {
			error_log("no content for " . $node->path);
			var_dump($node->content);
			return;
		}
		$filename = 'index.' . ($node->header("feed") ? "x" : "ht") . "ml";
		error_log($node->path. ' ' .$filename);
		$node->child( new ContentNode($filename) )->content = $node->content->body;
		$node->content = null;
	}
	public static function walk ($node) { parent::walk($node); }
}
