<?php

abstract class A {
	private static $root = null;
	private static $class = null;
	
	protected function start ($node) {}
	protected function finish ($node) {}
	
	protected function each ($node, $class = __CLASS__) {
		if ($class === __CLASS__) {
			error_log("Traversing the children doing nothing? That's stupid.");
			return;
		}
		if (!class_exists($class)) {
			error_log("Class doesn't exist: $class");
		}
		if (!empty($node->children)) {
			// error_log("Traversing with $c over $node");
			foreach ($node->children as $child) {
				// call_user_func(array($class, "each"), $child);
				self::each($child, $class);
			}
		}
		return call_user_func(array($class, "each"), $node);
	}
	
	
	private final function getClass () {
		// get the original class that was called.
		if (self::$class) {
			return self::$class;
		}
		self::$class = "B";
		
		$trace = debug_backtrace();
		var_dump($trace);
		die();
		
		// $trace = array_pop($trace);
		// $class = $trace['class'];
		
		return self::$class;
		
	}
	
	// I'm sorry.  Memory depands using ugly iteration for this.
	// What I wouldn't do for proper tail recursion in PHP!
	public function walk ($node) {
		$class = self::getClass();
		call_user_func(array($class, "start"), $node);
		self::each($node, $class);
		call_user_func(array($class, "finish"), $node);
	}
}
class B extends A {
	function each ($node) {
		error_log("each: {$node->id}");
	}
	function start ($node) {
		error_log("start: {$node->id}");
	}
	function finish ($node) {
		error_log("end: {$node->id}");
	}
}

$node = json_decode(json_encode(array(
	"id" => "root",
	"children" => array(
		array("id" => "child (childless)"),
		array(
			"id" => "kid",
			"children" => array(
				array("id" => "grandkid")
			)
		)
	)
)));

function walk ($node, $class) {
	A::walk($node, $class);
}

walk(1, "B");
