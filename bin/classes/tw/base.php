<?php

class TW_Base {
 	public static function walk ($node, $c = __CLASS__) {
		if ($node->length > 0) {
			if ($c === __CLASS__) {
				error_log("Traversing the children doing nothing? That's stupid.");
				return;
			}
			if (!class_exists($c)) {
				error_log("Class doesn't exist: $c");
			}
			// error_log("Traversing with $c over $node");
			foreach ($node->children as $child) {
				eval("$c::walk(\$child);");
			}
		}
	}
}

