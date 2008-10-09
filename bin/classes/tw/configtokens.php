<?php

class TW_ConfigTokens extends TW_Base {
	protected static function each ($node) {
		if (!$node->content || !$node->content->body) return;
		foreach (
			Config::getAll() as $key => $val
		) $node->content->body = str_ireplace(
			array('\\${' . $key . '}', '${' . $key . '}', "$\1{" . $key . "}"),
			array("$\1{" . $key . "}", $val, '${' . $key . '}'),
			$node->content->body
		);
	}
	public static function walk ($node) { parent::walk($node); }
}
