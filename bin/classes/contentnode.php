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
		return (
			in_array($key, self::$pseudoMembers)
		) ? $this->$key($val)
		: (
			in_array($key, self::$realMembers)
		) ? $this->$key = $val
		: (
			$key === "headers"
		) ? $this->setHeader($val)
		: $this->setHeader($key, $val);
	}
	public function __get ($key) {
		return $this->get($key);
	}
	protected function get ($key) {
		return (
			in_array($key, self::$pseudoMembers)
		) ? $this->$key()
		: (
			$key === "headers"
		) ? to_object($this->headers)
		: $this->header($key);
	}
	
	public function template ($tpl, $args___ = null, $buffer___ = false) {
		$node = $this;
		if ($args___) extract($args___, EXTR_SKIP);
		if ($buffer___) ob_start();
		require(Config::get("template") . "/$tpl");
		if ($buffer___) return ob_get_clean();
	}

	public function header ($header, $else = null) {
		if (
			!$this->headers
		) die("no headers! " . $this->json());
		return (
			property_exists($this->headers, $header)
		) ? $this->headers->$header : $else;
	}
	
	protected function json ($json = "") {
		return (
			$json = json_decode($json)
		) ? json_encode($this->data($json))
		: json_encode($this->data());
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

