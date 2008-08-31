<?php



class ContentNode implements Countable {
	// note: these are strictly internal.
	// see __get and __set for exposed public members (not all of which exist, exactly)
	public $content = null;

	private $name = "";
	private $children = null;
	private $parent = null;
	
	public function __construct ($name) {
		$this->children = new stdClass();
		if (is_a($name, 'stdClass')) {
			$this->data($name);
		} else {
			$this->name = $name;
		}
	}
	
	public function __get ($k) {
		if (in_array($k, array(
			// these have getter functions.
			'parent', 'data', 'json', 'path', 'length', 'count'
		))) {
			return $this->$k();
		} else {
			return $this->$k;
		}
	}
	public function __set ($k, $v) {
		if (in_array($k, array(
			// these have setter functions.
			'name', 'data', 'json', 'parent', 'path', 'child', 'transform', 'remove'
		))) {
			return $this->$k($v);
		}	else {
			trigger_error("Access to ContentNode::\$$k restricted", E_USER_ERROR);
		}
	}
	public function __toString () {
		return '[ContentNode] '. $this->name;
	}
	
	// just a handy syntax for grabbing a direct child by name.
	public function _ ($name, $create = false) {
		// error_log("getting child [$name] from $this->name");
		// error_log(var_dump(array_keys($this->children), 1));
		// 
		
		if (is_string($name) && property_exists($this->children, $name)) {
			return $this->children->$name;
		} elseif (is_object($name) && property_exists($this->chilren, $name->name)) {
			$name = $name->name;
			return $this->children->$name;
		} elseif ($create) {
			return $this->child( new ContentNode($name) );
		} else {
			return null;
		}
	}
	
	public function __ ($path, $create = false) {
		$n = $this;
		foreach (
			explode("/", $path) as $p
		) if (!$n) {
			break;
		} elseif ($p === '.' || $p === '') {
			continue;
		} elseif ($p === '..') {
			$n = $n->parent;
		} else {
			$n = $n->_($p, $create);
		}
		return $n;
	}
	
	public function header ($header, $else = null) {
		return (
			is_object($this->content) &&
			property_exists($this->content, "headers") &&
			is_object($this->content->headers) &&
			property_exists($this->content->headers, $header)
		) ? $this->content->headers->$header : $else;
	}
	
	private function json ($json = "") {
		$j = json_decode($json);
		if ($j === null) {
			// get
			return json_encode($this->data());
		} else {
			// set
			$this->data($j);
			return $json;
		}
	}
	private function data ($data = null) {
		if ($data === null) {
			// get
			$data = new stdClass();
			$data->name = $this->name;
			$data->content = $this->content;
			if (count(get_object_vars($this->children)) > 0) {
				$data->children = new stdClass();
				foreach ($this->children as $child) {
					$data->children->{$child->name} = $child->data();
				}
			}
		} else {
			// set
			$this->name($data->name);
			$this->content = $data->content;
			if (property_exists($data, "children")) {
				foreach ($data->children as $child) {
					$this->child($child);
				}
			}
		}
		return $data;
	}
	private function name ($name) {
		$this->name = $name;
		if ($this->parent) {
			$this->parent->rollCall();
		}
		return $name;
	}
	public function child ($child) {
		// error_log("child ".$this . ' ' . $child);
		
		if (is_a($child, get_class($this))) {
			$k = $child;
		} else {
			$k = new self($child);
		}
		if (property_exists($this->children, $k->name)) {
			$this->remove($this->_($k->name));
		}
		if ($this !== $k->parent) {
			$k->parent($this);
		}
		$this->children->{$k->name} = $k;
		return $child;
	}
	public function remove ($child) {
		if (is_a($child, get_class($this))) {
			$n = $child->name;
		} else {
			$n = $child;
		}
		if (property_exists($this->children, $n)) {
			unset($this->children->$n);
		}
		return $child;
	}
	public function rollCall () {
		foreach ($this->children as $name => $child) if ($name !== $child->name) {
			unset( $this->children->$name );
			$this->children->{$child->name} = $child;
		}
	}
	private function parent ($parent = false) {
		if ($parent === false ) {
			return $this->parent;
		} elseif ($parent !== $this->parent) {
			if ($this->parent) {
				// moving from another location, sever ties.
				$this->parent->remove($this);
			}
			$this->parent = $parent;
			$parent->child($this);
		}
		return $parent;
	}
	private function path () {
		return ($this->parent ? $this->parent->path() : '') . '/'  . $this->name;
	}
	public function count () {
		return $this->length();
	}
	private function length () {
		return count(get_object_vars($this->children));
	}
}
