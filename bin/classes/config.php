<?php

class Config {
	private static $conf = array();
	private static $fixed = array();
	private static $instance = null;
	
	public static function get ($key, $default = null) {
		$key = trim(strtolower($key));
		if (!array_key_exists($key, self::$conf)) {
			trigger_error("Warning: $key not found in config.", E_USER_WARNING);
			return $default;
		}
		return self::$conf[$key];
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
		if (!is_array($arr) && !is_object($arr)) return false;
		foreach ($arr as $k => $v) self::set($k, $v);
		return true;
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
	
	public static function unfix ($key) {
		if (in_array($key, self::$fixed)) unset( self::$fixed[array_search($key, self::$fixed)] );
	}
	public static function unfixa ($arr) {
		foreach ($arr as $a) self::unfix($a);
	}
	public static function unfixAll () {
		self::$fixed = array();
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

