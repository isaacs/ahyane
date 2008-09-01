<?php

class TW_Excerpt extends TW_Base {
	protected static function each ($node) {
		$body = $node->content->body;
		$node->content->excerpt = trim(
			(false !== strpos($body, '<!--more-->')) ?
				array_shift(explode('<!--more-->', $body)) :
				substr(strip_tags($body), 0, 250)
		);
	}
	public static function walk ($node) { parent::walk($node); }
}

