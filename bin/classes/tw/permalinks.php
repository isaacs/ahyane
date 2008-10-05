<?php

// right now, the permalink style for blogs is /YYYY/MM/url-slug, and is not configurable.
// Todo: make it configurable.

class TW_Permalinks extends TW_Base {
	private static $root = null;
	protected static function start ($node) {
		self::$root = $node;
	}
	protected static function finish ($node) {
		self::$root = null;
	}
	
	private static function getPermalink ($node) {
		
		return (
			$node->header("status") === "draft" ?
				"/drafts/" : ""
		) . (
			$node->header("type") === "blog" ?
				date(Config::get("permalinkprefix"), $node->content->headers->date) : ""
		) . $node->content->headers->slug;
	}
	
	protected static function each ($node) {
		if (
			is_object($node->content) && $node !== self::$root
		) $node->path = $node->content->headers->permalink = self::getPermalink($node);
	}
	
	public static function walk ($node) { parent::walk($node); }
}
