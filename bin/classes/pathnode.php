<?php

class PathNode extends ContentNode implements Countable {

	private $name = "";
	private $children = null;
	private $parent = null;
	
	private static $getters = array(
		'parent', 'data', 'json', 'path', 'length', 'count', 'root', 'href', 'content'
	);
	private static $setters = array(
		'name', 'data', 'json', 'parent', 'path', 'content'
	);
	
	
	public function __construct ($name) {
		$this->children = new stdClass();
		if (is_string($name)) {
			parent::__construct();
			$this->name = $name;
		} else parent::__construct($name);
	}
	
	public function __get ($k) {
		if (
			in_array($k, self::$getters)
		) return $this->$k();
		elseif (
			property_exists($this, $k)
		) return $this->$k;
		return parent::get($k);
	}
	public function __set ($k, $v) {
		if (
			in_array($k, self::$setters)
		) return $this->$k($v);
		else return parent::set($k, $v);
	}
	public function __toString () {
		return '[PathNode] '. $this->name;
	}
	
	
	
	// just a handy syntax for grabbing a direct child by name.
	public function _ ($name, $create = false) {
		if (
			is_string($name) && property_exists($this->children, $name)
		) return $this->children->$name;
		elseif (
			is_object($name) && property_exists($this->chilren, $name->name)
		) return $this->children->{$name->name};
		elseif (
			$create
		) return $this->child( new PathNode($name) );
		else return null;
	}
	
	public function __ ($path, $create = false) {
		$n = $this;
		
		foreach (
			explode("/", $path) as $p
		) if (
			!$n
		) break;
		elseif (
			$p === '.' || $p === ''
		) continue;
		elseif (
			$p === '..'
		) $n = $n->parent;
		else $n = $n->_($p, $create);
		
		return $n;
	}
	
	public function href () {
		return urlify(
			$this->path() . (
				false === strpos($this->name, ".") ? '/' : ''
			)
		);
	}
	private function content ($content = null) {
		return parent::data($content);
	}
	protected function data ($data = null) {
		$data = parent::data($data);
		
		if (
			property_exists($data, "name")
		) $this->name($data->name);
		$data->name = $this->name;
		
		if (
			property_exists($data, "children")
		) foreach (
			$data->children as $child
		) $this->child($child);
		$data->children = new stdClass();
		foreach (
			$this->children as $child
		) $data->children->{$child->name} = $child->data();
		
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
		
		if (
			is_a($child, get_class($this))
		) $k = $child;
		else $k = new self($child);
		
		if (
			$this !== $k->parent
		) $k->parent($this);
		
		// if (!$k->name) {
		// 	$trace = debug_backtrace();
		// 	foreach ($trace as $t) {
		// 		// error_log(join(" ", array_keys($t)));
		// 		@error_log("{$t['class']} {$t['function']} {$t['file']} {$t['line']}");
		// 	}
		// 	// var_dump(array_keys($trace[0]));
		// 	
		// 	// die();
		// }
		// 
		if ($k->name) $this->children->{$k->name} = $k;
		return $child;
	}
	public function remove ($child) {
		if (
			is_a($child, get_class($this))
		) $n = $child->name;
		else $n = $child;
		
		if (
			property_exists($this->children, $n)
		) unset($this->children->$n);
		
		return $child;
	}
	public function rollCall () {
		foreach (
			$this->children as $name => $child
		) if (
			$name !== $child->name
		) {
			unset( $this->children->$name );
			$this->children->{$child->name} = $child;
		}
	}
	private function parent ($parent = false) {
		if (
			$parent === false
		) return $this->parent;
		
		if (
			$parent !== $this->parent
		) {
			if ($this->parent) $this->parent->remove($this);
			$this->parent = $parent;
			if ($parent) $parent->child($this);
		}
		return $parent;
	}
	
	private static $root = null;
	private function root ($cached = true) {
		if ($cached && self::$root) return self::$root;
		for ($r = $n = $this; $n = $n->parent(); $r = $n);
		return (self::$root = $r);
	}
	
	// template function to load up all the static page nodes.
	private static $pages = null;
	public function getPages () {
		return (
			self::$pages ?
				self::$pages :
				(self::$pages = $this->getPageStep($this->root()))
		);
	}
	private function getPageStep ($root) {
		static $callback = null;
		if (!$callback) $callback = create_function(
			'$a,$b',
			'return $a[1] > $b[1] ? 1 : -1;'
		);
		$pages = array();
		foreach (
			$root->children as $child
		) if (
			$child->type === 'static'
		) $pages[] = array(
			$child->name,
			$child->header("sortorder", 0),
			$child->title,
			$child->permalink,
			$this->getPageStep($child)
		);
		usort($pages, $callback);
		return $pages;
	}
	
	private function path ($newpath = null) {
		if (
			!is_string($newpath)
		) return (
			$this->parent && $this->parent->parent ? $this->parent->path() : ''
		) . '/'  . $this->name;

		$newpath = explode("/", $newpath);
		$this->name(array_pop($newpath));
		$newpath = implode("/", $newpath);
		$this->parent( $this->root()->__($newpath, true) );
		return $this->path();
	}
	public function count () {
		return $this->length();
	}
	private function length () {
		return count(get_object_vars($this->children));
	}
}
