<?php

class TW_HomePage extends TW_Base {
	private static $posts = array();
	private static $homepage = null;
	private static $static_homepage = false;
	
	protected static function finish ($node) {
		if (self::$homepage) {
			$feed = $node->_(Config::get("feedslug"), true);
			$h = $node->headers;
			$h->archive = true;
			$h->feed = true;
			$feed->content = array(
				'headers' => $h,
				'body' => self::$posts
			);
			$node->content = self::$homepage->content;
			$feed->name = $feed->title = $feed->slug = Config::get("feedslug");
			$feed->feed = true;
			$feed->type = "";
			error_log(json_encode($feed->headers));
		} else $node->content = array(
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
			// looking for a static homepage, and this is it.
			!self::$homepage &&
			self::$static_homepage &&
			$node->name === self::$static_homepage &&
			$node->type === "static"
		) {
			self::$homepage = $node;
			$node->statichome = true;
		}
		if (
			// this is a blog post, so save it.
			$node->permalink &&
			$node->type !== "static"
		) self::$posts[] = new ContentNode( $node->content );
	}
	
	public static function walk ($node) { parent::walk($node); }
}
