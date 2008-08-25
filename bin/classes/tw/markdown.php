<?php

class TW_Markdown extends TW_Base {
	private static $parser = null;
	public static function walk ($node) {
		if (!self::$parser) {
			self::$parser = new Markdown_Parser;
		}
		$node->content['body'] = self::$parser->transform($node->content['body']);
		parent::walk($node, __CLASS__);
	}
}
