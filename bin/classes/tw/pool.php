<?php

// flatten everything into a single pool that can be pulled out of for permalinks, so it doesn't get in the way.
class TW_Pool extends TW_Base {
	
	private static $pool;
	private static $root;
	private static $dates = array();
	
	protected static function start ($node) {
		self::$pool = $node->child( new PathNode( "___pool" ) );
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
			!$node->body
		) return;
		
		$name = $n = $node->header("slug", $node->name);
		$i = 2;
		// make sure it's unique.
		while (
			self::$pool->_($n)
		) $n = "$name-" . ($i ++);
		
		if (
			$node->date
		) while (
			in_array($node->date, self::$dates)
		) $node->date ++;
		self::$dates[] = $node->date;
		
		$node->path = "/___pool/$n";
		$node->slug = $n;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
