<?php

class TW_Markdown extends TW_Base {
	private static $parser = null;
	protected static function each ($node) {
		if (!self::$parser) {
			self::$parser = new Markdown_Parser;
		}
		$node->content['body'] = self::$parser->transform($node->content['body']);
	}
	public static function walk ($node) { parent::walk($node); }
}
