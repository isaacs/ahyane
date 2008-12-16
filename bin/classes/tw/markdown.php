<?php

class TW_Markdown extends TW_Base {
	private static $parser = null;
	protected static function start ($node) {
		self::$parser = new Markdown_Parser;
	}
	protected static function each ($node) {
		if (
			!is_string($node->body) && !is_array($node->body)
		) return;
		
		if (
			is_string($node->body)
		) $node->body = self::$parser->transform($node->body);
		else foreach (
			$node->body as $key => $post
		) $node->body[$key]->body = self::$parser->transform($post->body);
	}
	public static function walk ($node) { parent::walk($node); }
}
