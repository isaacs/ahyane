<?php

class TW_Feed extends TW_Base {
	
	public static function each ($node) {
		if (
			$node->feed === true ||
			$node->archive !== true
		) return;
		$newnode = $node->_(Config::get("feedslug"), true);
		$newnode->body = $node->body;
		$newnode->feed = true;
	}
	
	public static function walk ($node) { parent::walk($node); }
}