<?php

class TW_ParseContent extends TW_Base {
	protected static function each ($node) {
		$node->content = Parser::parse($node->content);
	}
	public static function walk ($node) { parent::walk($node); }
}

