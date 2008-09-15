<?php

// flatten everything into a single pool that can be pulled out of for permalinks, so it doesn't get in the way.
class TW_Pool extends TW_Base {
	
	private static $pool;
	private static $root;
	
	protected static function start ($node) {
		self::$pool = $node->child( new ContentNode( "___pool" ) );
		self::$pool->content = "___pool";
		self::$root = $node;
	}
	protected static function finish ($node) {
		foreach (
			$node->children as $child
		) if (
			$child !== self::$pool
		) $node->remove($child);
		
		self::$pool = null;
		self::$root = null;
	}
	
	protected static function each ($node) {
		
		if (
			$node === self::$root ||
			$node === self::$pool ||
			!(
				is_object($node->content) &&
				property_exists($node->content, "body") &&
				$node->content->body
			)
		) return;
		
		$name = $n = $node->header("slug", $node->name);
		$i = 2;
		// make sure it's unique.
		while ( self::$pool->_($n) ) {
			$n = "$name-$i";
			$i ++;
		}
		$node->path = "/___pool/$n";
		$node->content->headers->slug = $n;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
