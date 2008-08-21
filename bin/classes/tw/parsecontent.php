<?php

class TW_ParseContent extends TW_Base {
	public static function walk ($node) {
		$node->content = Parser::parse($node->content);
		parent::walk($node, __CLASS__);
	}
}

