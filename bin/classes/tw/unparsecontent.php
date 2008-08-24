<?php

class TW_UnParseContent extends TW_Base {
	public static function walk ($node) {
		if ($node->content && is_array($node->content)) {
			// error_log(substr(json_encode($node->content), 0, 100));
			
			$node->content = $node->content['body'];
		} else {
			$node->content = null;
		}
		parent::walk($node, __CLASS__);
	}
}

