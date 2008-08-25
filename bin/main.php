<?php

if (defined('AHYANE_LOADED')) {
	return;
}
define('AHYANE_LOADED', true);

function ahyane_autoloader ($class) {
	$filename = realpath(dirname(__FILE__) . '/classes/' . strtolower(
		str_replace(array('_','.'), array('/',''), $class) . '.php'));
	if ($filename && include_once($filename)) {
		return true;
	} else {
		trigger_error('Could not load Class [' . $class . '] from ' . $filename);
		return false;
	}	
}

spl_autoload_register('ahyane_autoloader');

