<?php

if (defined('AHYANE_LOADED')) {
	return;
}
define('AHYANE_LOADED', true);

function ahyane_autoloader ($class) {
	$base = dirname(__FILE__) . '/classes/';
	
	$class_raw = strtolower(str_replace('.', '', $class));
	$class_trans = str_replace('_', '/', $class_raw);
	$file_raw = realpath($base . $class_raw . '.php');
	$file_trans = realpath($base . $class_trans . '.php');
	
	$filename = file_exists($file_raw) ? $file_raw : $file_trans;
	
	if ($filename && include_once($filename)) {
		return true;
	} else {
		trigger_error('Could not load Class [' . $class . '] from ' . $filename);
		return false;
	}	
}

spl_autoload_register('ahyane_autoloader');

function to_object ($x) {
	return (is_object($x) || is_array($x)) ? (object)json_decode(json_encode($x)) : (object) $x;
}
