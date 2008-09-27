<?php

// a sorry stand-in for a template engine.
class TW_ViewLayer extends TW_Base {
	protected static function each ($node) {
		if ($node->content && is_object($node->content)) {
			// $node->content = print_r($node->content, 1);
			ob_start();
			include(AHYANE_BASEDIR . '/tpl/index.php');
			$node->content = ob_get_contents();
			ob_end_clean();
		} else {
			$node->content = null;
		}
	}
	public static function walk ($node) { parent::walk($node); }
}

