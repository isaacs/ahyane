<?php

class Parser {

	function parseHeaders ($rawhead) {
		$headers = array();
		foreach (explode("\n", $rawhead) as $header) {
			$header = explode(":", $header);
			$name = strtolower(trim(array_shift($header)));
			if (!preg_match('~^[a-zA-Z0-9]~', $name)) continue;
			$val = trim(implode(":", $header));
			if (array_key_exists($name, $headers)) {
				$headers[$name] = array($headers[$name]);
				$headers[$name][] = $val;
			} else {
				$headers[$name] = $val;
			}
		}
		return $headers;
	}
	
	function parse ($content) {
		$parts = explode("\n\n", $content);
		// echo "parts\n";
		// var_dump($parts);
		// die();
		return array(
			'headers' => self::parseHeaders(array_shift($parts)),
			'body' => (count($parts) > 0) ? trim(implode("\n\n", $parts)) : null
		);
	}
	
	function read ($filename) {
		// if (!is_readable($filename)) {
		// 	echo "\nnot readable: $filename";
		// 	die();
		// }
		return (!is_readable($filename) || !is_file($filename)) ?
			null :
			self::parse( file_get_contents($filename) );
	}
	
}
