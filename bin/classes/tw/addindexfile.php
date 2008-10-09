<?php

class TW_AddIndexFile extends TW_Base {
	protected static function each ($node) {
		if (
			!$node->content || !$node->content->body
		) return;
		$filename = 'index.' . ($node->header("feed") ? "x" : "ht") . "ml";
		$node->child( new ContentNode($filename) )->content = $node->content->body;
		$node->content = null;
	}
	public static function walk ($node) { parent::walk($node); }
}
