<?php

class ContentNode {
	
	private $headers = null;
	public $body = null;
	public $excerpt = null;
	
	private static $pseudoMembers = array( 'json', 'data' );
	private static $realMembers = array( 'body', 'excerpt' );
	
	public function __construct ($data = null) {
		$this->headers = new stdClass();
		if ($data) $this->data($data);
	}
	public function setHeader ($key, $val = null) {
		if (
			$val === null &&
			(is_array($key) || is_object($key))
		) foreach (
			$key as $k => $v
		) $this->set($k, $v);
		elseif (
			is_string($key)
		) $this->headers->$key = $val;
		return $val;
	}
	public function __set ($key, $val) {
		return $this->set($key, $val);
	}
	protected function set ($key, $val) {
		// if (is_string($key) && is_string($val)) {
		// 	error_log("set $key $val");
		// } else {
		// 	error_log("problem? $key = ".json_encode($key)." val=".json_encode($val));
		// }
		
		//
		if (
			in_array($key, self::$pseudoMembers)
		) return $this->$key($val);
		
		if (
			in_array($key, self::$realMembers)
		) return $this->$key = $val;
		
		if (
			$key === "headers"
		) return $this->setHeader($val);
		
		return $this->setHeader($key, $val);
	}
	public function __get ($key) {
		return $this->get($key);
	}
	protected function get ($key) {
		if (
			in_array($key, self::$pseudoMembers)
		) return $this->$key();
		
		if (
			$key === "headers"
		) return to_object($this->headers);
		
		return $this->header($key);
	}
	
	public function template ($tpl, $buffer = false) {
		$node = $this;
		if ($buffer) ob_start();
		require(Config::get("template") . "/$tpl");
		if ($buffer) return ob_get_clean();
	}

	public function header ($header, $else = null) {
		if (!$this->headers) {
			error_log("no headers! " . $this->json);
			die();
		}
		return (
			property_exists($this->headers, $header)
		) ? $this->headers->$header : $else;
	}
	
	protected function json ($json = "") {
		if (
			$json = json_decode($json)
		) return json_encode($this->data($json));
		return json_encode($this->data());
	}
	protected function data ($data = null) {
		$data = (object)$data;
		foreach (
			array('excerpt', 'body', 'headers') as $thingie
		) if (
			property_exists($data, $thingie)
		) $this->set($thingie, $data->$thingie);
		else $data->$thingie = $this->$thingie;
		return $data;
	}
	
}

