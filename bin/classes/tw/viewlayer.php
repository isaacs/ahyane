<?php

class TW_ViewLayer extends TW_Base {
	protected static function each ($node) {
		if (
			$node->viewlayerapplied || 
			!$node->body
		) return;
		$node->body = $node->template("index.php", null, true);
		$node->viewlayerapplied = true;
	}
	public static function walk ($node) { parent::walk($node); }
}

