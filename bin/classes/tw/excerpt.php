<?php

class TW_Excerpt extends TW_Base {
	protected static function each ($node) {
		$body = $node->content->body;
		if (false !== strpos($body, '<!--more-->')) {
			$excerpt = explode('<!--more-->', $body);
			$excerpt = $excerpt[0];
		} else {
			$excerpt = strip_tags($body);
			$excerpt = substr($excerpt, 0, 250);
		}
		$node->content->excerpt = trim($excerpt);
	}
	public static function walk ($node) { parent::walk($node); }
}

