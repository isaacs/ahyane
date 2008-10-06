<?php

class TW_ConfigTokens extends TW_Base {
	protected static function each ($node) {
		if (!$node->content) return;
		foreach (Config::getAll() as $key => $val) {
			$node->content = str_ireplace(
				'\\${'.$key.'}', '$\\{' . $key . '}', $node->content
			);
			$node->content = str_ireplace(
				'${'.$key.'}', $val, $node->content
			);
			$node->content = str_ireplace(
				'$\\{'.$key.'}', '${' . $key . '}', $node->content
			);
			
		}
	}
	public static function walk ($node) { parent::walk($node); }
}
