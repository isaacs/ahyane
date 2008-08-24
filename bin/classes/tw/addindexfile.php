<?php

class TW_AddIndexFile extends TW_Base {
	public static function walk ($node) {
		// parse children first, so as not to re-parse the index.html file.
		// DFS.
		parent::walk($node, __CLASS__);
		if ($node->content) {
			$node->child = new ContentNode('index.html');
			$node->_('index.html')->content = $node->content;
			$node->content = null;
			// error_log("has content: $node {$node->path}");
			
		}
	}
}
