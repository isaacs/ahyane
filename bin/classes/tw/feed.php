<?php

class TW_Feed extends TW_Base {
	
	public static function each ($node) {
		if (
			$node->header("feed") === true ||
			$node->header("archive") !== true
		) return;
		$newnode = $node->_(Config::get("feedslug"), true);
		$newnode->content = to_object($node->content);
		$newnode->content->headers->feed = true;
	}
	
	public static function walk ($node) { parent::walk($node); }
}