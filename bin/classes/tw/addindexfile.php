<?php

class TW_AddIndexFile extends TW_Base {
	protected static function each ($node) {
		if (!$node->content) return;
		$node->child( new ContentNode('index.html') );
		$node->_('index.html')->content = $node->content;
		$node->content = null;
	}
	public static function walk ($node) { parent::walk($node); }
}
