<?php

class TW_HomePage extends TW_Base {
	private static $posts = array();
	protected static function finish ($node) {
		$node->content = to_object(array(
			'headers' => array(
				'home' => true,
				'archive' => true
			),
			'body' => self::$posts
		));
	}
	
	protected static function each ($node) {
		if (
			!$node->header("permalink") ||
			$node->header("type") === "static"
		) return;
		self::$posts[] = to_object($node->content);
	}
	
	public static function walk ($node) { parent::walk($node); }
}
