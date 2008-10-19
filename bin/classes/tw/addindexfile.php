<?php

class TW_AddIndexFile extends TW_Base {
	protected static function each ($node) {
		if (!$node->body) return;
		$filename = 'index.' . ($node->feed ? "x" : "ht") . "ml";
		$node->child( new PathNode($filename) )->body = $node->body;
		$node->body = null;
	}
	public static function walk ($node) { parent::walk($node); }
}
