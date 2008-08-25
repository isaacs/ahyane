<?php

class TW_Excerpt extends TW_Base {
	public static function walk ($node) {
		$body = $node->content['body'];
		if (false !== strpos($body, '<!--more-->')) {
			$excerpt = explode('<!--more-->', $body);
			$excerpt = $excerpt[0];
		} else {
			$excerpt = strip_tags($body);
			$excerpt = substr($excerpt, 0, 250);
		}
		$node->content['excerpt'] = trim($excerpt);
		
		parent::walk($node, __CLASS__);
	}
}

