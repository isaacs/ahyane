<?php
require_once(dirname(__FILE__) . '/contentnode.php');
class FileTree {
	private static $trunk = null;
	
	private static $unparseable = "throw 1; '<foo'hack</script>";
	
	private function __construct () {}
	
	public static function readCache ($key = '') {
		$file = dirname(__FILE__) . '/cache/cache-' . md5($key) . '.json';
		$data = file_get_contents($file);
		$data = substr($data, count(self::$unparseable));
		return new ContentNode( json_decode($data) );
	}
	public static function writeCache ($tree) {
		$file = dirname(__FILE__) . '/cache/cache-' . md5($tree->name) . '.json';
		file_put_contents($file, self::$unparseable . $tree->json);
	}
	
	public static function read ($root) {
		$path = realpath($root);
		if (!$path) return;
		
		$d = new PathNode(basename($path));
		
		$d->filename = $path;
		$d->modified = filemtime($path);
		
		if (
			!is_dir($path)
		) $d->body = file_get_contents($path);
		else foreach (
			glob("$path/*") as $file
		) $d->child(
			self::read("$root/" . basename($file))
		);
		// error_log("read: " . json_encode($d->headers));
		// die();
		return $d;
	}
	public static function write ($tree, $root = "") {
		$path = realpath($root);
		if (!$path) return;
		
		if (! is_object($tree)) {
			trigger_error(
				"Asking to write a tree that isn't a tree?", E_USER_WARNING
			);
			echo json_encode($tree);
			die();
		}
		// $name = str_replace('%2F', '/', $tree->name);
		$name = $tree->name;
		
		$path .= "/" . $name;
		$root .= "/" . $name;
		
		if ($tree->length <= 0 && !$tree->body) return;
		
		if (
			$tree->body
		) if (
			$tree->length > 0
		) trigger_error(
			"Warning: content in $tree is lost, because it has children", E_USER_WARNING
		);
		else return file_put_contents($path, $tree->body);
		
		if (
			is_file($path)
		) unlink($path);
		
		if (
			!is_dir($path)
		) mkdir($path, 0755, true);
		
		foreach (
			$tree->children as $child
		) self::write($child, $root);
	}
}
