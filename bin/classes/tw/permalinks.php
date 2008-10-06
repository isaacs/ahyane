<?php

class TW_Permalinks extends TW_Base {
	private static $root = null;
	protected static function start ($node) {
		self::$root = $node;
	}
	protected static function finish ($node) {
		self::$root = null;
	}
	
	private static function getBlogPermalink ($node) {
		$parts = explode("%slug%", Config::get("permalink"));
		foreach (
			$parts as $i => $part
		) $parts[$i] = date($part, $node->content->headers->date);
		return implode($node->content->headers->slug, $parts);
	}
	private static function getPermalink ($node) {
		return (
			$node->header("status") === "draft" ?
				"/drafts/" : ""
		) . (
			$node->header("type") === "blog" ?
				self::getBlogPermalink($node) :
				$node->content->headers->slug
		);
	}
	
	protected static function each ($node) {
		if (
			!is_object($node->content) || $node === self::$root
		) return;
		$node->path = self::getPermalink($node);
		$node->content->headers->permalink = $node->href;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
