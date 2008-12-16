<?php

class TW_Excerpt extends TW_Base {
	private static $parser = null;
	protected static function start ($node) {
		self::$parser = new Markdown_Parser;
	}
	protected static function each ($node) {
		$body = $node->body;
		$node->excerpt = self::$parser->transform(trim(
			(false !== strpos($body, '<!--more-->')) ?
				strip_tags(array_shift(explode('<!--more-->', $body))) :
				substr(strip_tags($body), 0, Config::get("excerptlength"))
		));
	}
	public static function walk ($node) { parent::walk($node); }
}

