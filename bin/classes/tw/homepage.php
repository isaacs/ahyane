<?php

class TW_HomePage extends TW_Base {
	private static $posts = array();
	private static $homepage = null;
	private static $static_homepage = false;
	
	protected static function finish ($node) {
		$node->content = self::$homepage ? self::$homepage->content : array(
			'headers' => array('archive' => true),
			'body' => self::$posts
		);
		$node->home = true;
		if (self::$homepage) self::$homepage->permalink = urlify("/");
		$node->permalink = urlify('/');
	}
	
	protected static function start ($node) {
		self::$static_homepage = @Config::get("homepage", false);
	}
	
	protected static function each (&$node) {
		if (
			// found it already
			self::$homepage
		) return;
		elseif (
			// looking for a static homepage, and this is it.
			self::$static_homepage &&
			$node->name === self::$static_homepage &&
			$node->type === "static"
		) {
			self::$homepage = $node;
			$node->statichome = true;
		} elseif (
			// this is a blog post, so save it.
			$node->permalink &&
			$node->type !== "static"
		) self::$posts[] = new ContentNode( $node->content );
	}
	
	public static function walk ($node) { parent::walk($node); }
}
