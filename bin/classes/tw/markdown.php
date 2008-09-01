<?php

class TW_Markdown extends TW_Base {
	private static $parser = null;
	protected static function each ($node) {
		if (
			!is_object($node->content) ||
			!property_exists($node->content, "body") ||
			!(
				is_string($node->content->body) ||
				is_array($node->content->body)
			)
		) return;
		
		if (
			!self::$parser
		) self::$parser = new Markdown_Parser;
		
		if (
			is_string($node->content->body)
		) $node->content->body = self::$parser->transform($node->content->body);
		else foreach (
			$node->content->body as $key => $post
		) $node->content->body[$key]->body = self::$parser->transform($post->body);
	}
	public static function walk ($node) { parent::walk($node); }
}
