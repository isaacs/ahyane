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
			$node->content->headers->status === "draft" ?
				"drafts/" : ""
		) . (
			$node->content->headers->type === "blog" ?
				date("Y/m/", $node->content->headers->date) : ""
		) . $node->content->headers->slug;
	}
	
	private static function move ($node, $dest) {
		$dest = explode("/", $dest);
		$node->name = array_pop($dest);
		$dest = implode("/", $dest);
		$node->parent = self::$root->__($dest, true);
		error_log($node->path);
	}
	
	protected static function each ($node) {
		// error_log("Posts walker: ". $node->path);
		if (
			is_object($node->content) && $node !== self::$root
		) self::move( $node,
			$node->content->headers->permalink = self::getPermalink($node)
		);
	}
	
	public static function walk ($node) { parent::walk($node); }
}
