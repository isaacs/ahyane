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
		$path = realpath(dirname(__FILE__) . '/../../' . $root);
		if (!$path) {
			// error_log("no path " . json_encode($path));
			return;
		}
		// error_log("got path ".$path);
		$d = new ContentNode(basename($path));
		if (!is_dir($path)) {
			// error_log("getting contents, because not a dir");
			$d->content = file_get_contents($path);
		} else {
			foreach (glob("$path/*") as $file) {
				// error_log("reading $root/" . basename($file));
				$child = self::read($root . '/' . basename($file));
				// error_log("after read ". $child);
				$d->child($child);
				// error_log("after child assignment");
			}
		}
		// error_log("returning $d");
		return $d;
	}
	public static function write ($tree, $root = "") {
		$path = realpath(dirname(__FILE__) . '/../../' . $root);
		if (!$path) {
			return;
		}
		$path .= "/" . $tree->name;
		$root .= "/" . $tree->name;
		
		if ($tree->length > 0) {
			// must be a directory.
			if ($tree->content) {
				trigger_error("Warning: content in $tree is lost, because it has children", E_USER_WARNING);
			}
			if (is_file($path)) {
				unlink($path);
			}
			if (!is_dir($path)) {
				mkdir($path, 0755, true);
			}
			foreach ($tree->children as $child) {
				self::write($child, $root);
			}
		} elseif ($tree->content) {
			file_put_contents($path, $tree->content);
		}
	}
}

// 
// FileTree::writeCache(FileTree::read("content"));



