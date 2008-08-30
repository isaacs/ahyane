<?php

class TW_Base {
	private static $class = null;
	
	protected static function start ($node) {}
	protected static function finish ($node) {}
	protected static function each ($node) {}
	
	// This MUST be overridden in child classes with:
	//   public static function walk ($node) { parent::walk($node); }
	// Stupid, I know.
	public static function walk ($node) {
		$class = self::getClass();
		call_user_func(array($class, "start"), $node);
		self::_each($node, $class);
		call_user_func(array($class, "finish"), $node);
		self::$class = null;
	}
	
	private final static function _each ($node) {
		$class = self::getClass();
		if ($node->length) {
			foreach ($node->children as $child) {
				self::_each($child, $class);
			}
		}
		return call_user_func(array($class, "each"), $node);
	}
	
	// with late binding, this wouldn't be necessary.
	// GRRR!!
	// Todo: when php 5.3 is stable/common, rip out this kluge.
	private final static function getClass () {
		// get the original class that was called.
		if (self::$class) {
			return self::$class;
		}
		
		// walk up the call stack to find the child that was called.
		$class = "";
		foreach (debug_backtrace() as $i => $t) {
			if ($i === 0) continue;
			if (
				! array_key_exists('class', $t) ||
				! ($t['class'] === __CLASS__ || in_array(__CLASS__, class_parents($t['class']))) ||
				! ($t['function'] === 'walk') ||
				! ($t['type'] === '::')
			) break;
			$class = $t['class'];
		}
		
		if (!$class || $class === __CLASS__) {
			trigger_error("The 'walk' function must be overridden on child classes. Do not call on the " . 
				__CLASS__ . " class directly.", E_USER_ERROR);
			return;
		}
		if (!class_exists($class)) {
			trigger_error("Class doesn't exist: $class", E_USER_ERROR);
			return;
		}
		
		return (self::$class = $class);
	}

}

