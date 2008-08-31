<?php

// right now, the permalink style is /YYYY/MM/url-stub, and is not configurable.
// Todo: make it configurable.

class TW_Posts extends TW_Base {
	
	protected static function before ($node) {
		self::$root = $node;
	}
	
	protected static function each ($node) {
		error_log("Posts walker: ". $node->path);
		
		
	}
	
	public static function walk ($node) { parent::walk($node); }
}
