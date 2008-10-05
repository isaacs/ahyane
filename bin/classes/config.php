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
	
	
	public static function get ($key, $default = null) {
		return array_key_exists($key, self::$conf) ? self::$conf[$key] : $default;
	}
	public static function geta ($arr) {
		$ret = array();
		foreach (
			$arr as $key
		) if (
			array_key_exists($key, self::$conf)
		) $ret[$key] = self::get($key);
		return $ret;
	}
	public static function getAll () {
		// do it this way so it's a copy, not a leak
		return self::geta( array_keys(self::$conf) );
	}
	public static function set ($key, $val) {
		$key = trim(strtolower($key));
		return (
			in_array($key, self::$fixed) && array_key_exists($key, self::$conf)
		) ? null : (self::$conf[$key] = $val);
	}
	public static function seta ($arr) {
		foreach ($arr as $k => $v) self::set($k, $v);
	}
	public static function fix ($key) {
		if (!in_array($key, self::$fixed)) self::$fixed[] = $key;
	}
	public static function fixa ($arr) {
		foreach ($arr as $a) self::fix($a);
	}
	public static function fixAll () {
		self::fixa(array_keys(self::$conf));
	}
	public static function read ($filename) {
		return self::seta( Parser::readHeaderFile($filename) );
	}
	// pass in an $argv array
	// set any --foo=bar variables, and returns the rest
	public static function readArgs ($argv) {
		$ret = array();
		
		foreach (
			$argv as $arg
		) if ( substr($arg, 0, 2) === '--' ) {
			$arg = explode( "=", preg_replace("~^-+~", '', $arg) );
			self::set( array_shift($arg), implode("=", $arg) );
		} else $ret[] = $arg;
		
		return $ret;
	}
}

