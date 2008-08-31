<?php

class Parser {

	function parseHeaders ($rawhead) {
		$headers = new stdClass();
		foreach (explode("\n", $rawhead) as $header) {
			$header = explode(":", $header);
			$name = strtolower(trim(array_shift($header)));
			if (!preg_match('~^[a-z0-9]~', $name)) continue;
			$val = trim(implode(":", $header));
			if (property_exists($headers, $name)) {
				$headers->$name = array($headers->$name);
				array_push($headers->$name, $val);
			} else {
				$headers->$name = $val;
			}
		}
		return $headers;
	}
	
	function parse ($content) {
		if (is_object($content) && !is_null($content)) {
			error_log(" - - - This is odd.  Parsing parsed content?");
			// var_dump($content);
			// $trace = debug_backtrace();
			// foreach ($trace as $i => $t) {
			// 	$trace[$i]['args'] = substr(json_encode($t['args']), 0, 500);
			// }
			// var_dump($trace);
			// die();
			return $content;
		}
		
		
		
		$parts = explode("\n\n", $content);

		
		return to_object(array(
			'headers' => self::parseHeaders(array_shift($parts)),
			'body' => (count($parts) > 0) ? trim(implode("\n\n", $parts)) : null
		));
	}
	
	function read ($filename) {
		return (!is_readable($filename) || !is_file($filename)) ?
			null :
			self::parse( file_get_contents($filename) );
	}
	
}
