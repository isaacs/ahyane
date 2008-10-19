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
		) $parts[$i] = date($part, $node->date);
		return implode($node->slug, $parts);
	}
	private static function getPermalink ($node) {
		return (
			$node->status === "draft" ?
				"/drafts/" : ""
		) . (
			$node->type === "blog" ?
				self::getBlogPermalink($node) :
				$node->slug
		);
	}
	
	protected static function each ($node) {
		if (
			!$node->body || $node === self::$root
		) return;
		$node->path = $node->path = self::getPermalink($node);
		$node->permalink = $node->href;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
