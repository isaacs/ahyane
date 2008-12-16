<?php

class TW_Permalinks extends TW_Base {
	private static $root = null;
	protected static function start ($node) {
		self::$root = $node;
	}
	private static $pages = array();
	protected static function finish ($node) {
		// iterate over all the pages to handle child pages.
		foreach (
			self::$pages as $slug => $page
		) if (
			$page->parentpage &&
			array_key_exists($page->parentpage, self::$pages)
		) {
			self::$pages[$page->parentpage]->child($page);
			$page->permalink = $page->href;
		}
		
		self::$root = null;
	}
	
	private static function getBlogPermalink ($node) {
		$parts = explode("%slug%", Config::get("permalink"));
		foreach (
			$parts as $i => $part
		) $parts[$i] = date($part, $node->date);
		return implode($node->slug, $parts);
	}
	private static function getStaticPermalink ($node) {
		self::$pages[$node->slug] = $node;
		return $node->slug;
	}
	private static function getPermalink ($node) {
		return (
			$node->status === "draft" ?
				"/drafts/" : ""
		) . (
			$node->type === "blog" ?
				self::getBlogPermalink($node) :
				self::getStaticPermalink($node)
		);
	}
	
	protected static function each ($node) {
		if (
			!$node->body || $node === self::$root
		) return;
		$node->path = self::getPermalink($node);
		$node->permalink = $node->href;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
