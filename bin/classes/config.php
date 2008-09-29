<?php

class Config {
	private static $conf = array();
	private static $fixed = array();
	private static $instance = null;

	// Part of me things that Config::_()->foo is just too many special chars in a row..
	// public function __get ($key) {
	// 	return self::get($key);
	// }
	// public function __set ($key, $val) {
	// 	return self::set($key, $val);
	// }
	// public static function _ () {
	// 	return self::$instance ? self::$instance : (self::$instance = new self());
	// }
	
	
	public static function get ($key) {
		return in_array($key, self::$conf) ? self::$conf[$key] : null;
	}
	public static function set ($key, $val) {
		return in_array($key, self::$fixed) ? null : (self::$conf[$key] = $val);
	}
	public static function seta ($arr) {
		foreach ($arr as $k => $v) self::set($k, $v);
	}
	public static function fix ($key) {
		if (!in_array($key, self::$fixed)) self::$fixed[] = $key;
	}
	public static function read ($filename) {
		return self::seta( Parser::read($filename)->headers );
	}
}
