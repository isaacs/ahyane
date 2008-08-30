<?php

// a sorry stand-in for a template engine.
class TW_UnParseContent extends TW_Base {
	protected static function each ($node) {
		if ($node->content && is_array($node->content)) {
			$node->content = $node->content['body'];
		} else {
			$node->content = null;
		}
	}
	public static function walk ($node) { parent::walk($node); }
}

