<?php

class A {
	function walk ($node) {
		if (array_key_exists("children", $node)) {
			self::walk($node['children']);
		}
	}
}
class B extends A {
	function walk ($node) {
		error_log("got node {$node['id']}");
		if (array_key_exists("children", $node)) {
			self::walk($node['children']);
		}
	}
}
$node = array(
	"id" => "root",
	"children" => array(
		"id" => "child"
	)
);

B::walk($node);
		

