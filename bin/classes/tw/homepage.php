<?php

class TW_HomePage extends TW_Base {
	private static $posts = array();
	private static $homepage = null;
	private static $static_homepage = false;
	
	protected static function finish ($node) {
		$node->content = to_object(
			self::$homepage ?
			self::$homepage->content :
			array(
				'headers' => array('archive' => true),
				'body' => self::$posts
			)
		);
		$node->content->headers->home = true;
	}
	
	protected static function start ($node) {
		self::$static_homepage = @Config::get("homepage", false);
	}
	
	protected static function each ($node) {
		if (
			// found it already
			self::$homepage
		) return;
		elseif (
			// looking for a static homepage, and this is it.
			self::$static_homepage &&
			$node->name === self::$static_homepage &&
			$node->header("type") === "static"
		) self::$homepage = $node;
		elseif (
			// this is a blog post, so save it.
			$node->header("permalink") &&
			$node->header("type") !== "static"
		) self::$posts[] = $node->content;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
