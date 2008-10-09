<?php

// a sorry stand-in for a template engine.
class TW_ViewLayer extends TW_Base {
	protected static function each ($node) {
		if ($node->header("viewlayerapplied")) return;
		if (
			$node->content &&
			is_object($node->content) &&
			property_exists($node->content, "body")
		) {
			$node->content->body = $node->template("index.php", true);
			$node->content->headers->viewlayerapplied = true;
		} else $node->content = null;
	}
	public static function walk ($node) { parent::walk($node); }
}

