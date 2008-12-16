<?php

if (defined('AHYANE_LOADED')) {
	return;
}
define('AHYANE_LOADED', true);

define('AHYANE_BASEDIR', realpath(dirname(__FILE__) . '/../'));
chdir(AHYANE_BASEDIR);

function ahyane_autoloader ($class) {
	$base = AHYANE_BASEDIR . '/bin/classes/';
	
	$class_raw = strtolower(str_replace('.', '', $class));
	$class_trans = str_replace('_', '/', $class_raw);
	$file_raw = realpath($base . $class_raw . '.php');
	$file_trans = realpath($base . $class_trans . '.php');
	
	$filename = file_exists($file_raw) ? $file_raw : $file_trans;
	
	if ($filename && include_once($filename)) {
		return true;
	} else {
		trigger_error('Could not load Class [' . $class . '] from ' . $filename, E_USER_ERROR);
		return false;
	}	
}

spl_autoload_register('ahyane_autoloader');

function to_object ($x) {
	return (object) ((is_object($x) || is_array($x)) ? json_decode(json_encode($x)) : $x);
}

function urlify ($url) {
	$url = parse_url(Config::get("SiteURL") . '/' . $url);
	return (
		array_key_exists('scheme', $url) ? $url['scheme'] . '://' . $url['host'] : ''
	) . preg_replace('~/{2,}~', '/', $url['path']);
}

function slugify ($str) {
	return preg_replace(
		'~[^a-zA-Z0-9]+~', '-', str_replace(
			array('%20', '%'), array(' ', ''), rawurlencode(strtolower(trim($str)))
		)
	);
}