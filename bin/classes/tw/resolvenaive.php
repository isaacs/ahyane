<?php

// first pass simplifying the filetree into a node tree.
// later, we'll have to undo this with a bunch of index.html files all over the place,
// but for now, this makes it easiest to work with.

// /asdf/foo.txt --> asdf/foo
// /asdf/foo/index.txt --> asdf/foo
// /asdf/foo/bar.txt --> asdf/foo/bar
class TW_ResolveNaive extends TW_Base {
	public static function walk ($node) {
		// error_log("resolveNaive: $node ". $node->path);
		if ($node->name === "index.txt") {
			if ($node->parent !== null) {
				$node->parent->content = $node->content;
				$node->parent->remove($node);
			} else {
				trigger_error("Strangeness. Orphaned index.txt file.", E_USER_WARNING);
			}
		} elseif (($node->name[0] === '.' || $node->content === null) && $node->parent) {
			$node->parent->remove($node);
		}
		$node->name = preg_replace('~([^\.]+)\..*$~', '$1', $node->name);
		
		// process the children in the same way.
		parent::walk($node, __CLASS__);
		
		if ($node->length === 0 && $node->content === null && $node->parent) {
			$node->parent->remove($node);
		}
	}
}