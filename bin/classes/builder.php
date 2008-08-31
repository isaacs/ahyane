<?php

class Builder {
	
	private static $content = null;
	private static $htdocs = null;
	
	public static function make ($argv = array()) {
		error_log("make " . print_r($argv,1));
		if (empty($argv)) {
			$argv = array('full');
		}
		if (is_string($argv)) {
			$argv = array($argv);
		}
		foreach ($argv as $arg) {
			if (method_exists("Builder", $arg)) {
				Builder::$arg();
			}
		}
	}
	
	private static function emptyDir ($dir) {
		$dir = dirname(__FILE__) . "/$dir/";
		self::rm_recurse($dir);
		mkdir($dir);
	}
	/**
	 * Remove recursively. (Like `rm -r`)
	 * @see Comment by davedx at gmail dot com on {http://us2.php.net/manual/en/function.rmdir.php}
	 * @param file {String} The file or folder to be deleted.
	 **/
	function rm_recurse($file) {
		if (is_dir($file) && !is_link($file)) {
			foreach(glob($file.'/*') as $sf) {
				if ( !self::rm_recurse($sf) ) {
					error_log("Failed to remove $sf");
					return false;
				}
			}
			return rmdir($file);
		} else {
			return unlink($file);
		}
	}
	
	
	
	private static function clean () {
		self::emptyDir("cache");
	}
	private static function remove () {
		self::emptyDir("../../htdocs");
	}
	private static function full () {
		Builder::make(array(
			'remove',
			'read',
			'parse', ///////
			
			// insert magic here...
			
			'urlify',
			'write'
			// ,'display' // debuggery.
		));
	}
	private static function parse () {
		TW_ParseContent::walk(self::$htdocs);
	}
	
	private static function pool () {
		TW_Pool::walk(self::$htdocs);
	}
	
	private static function permalinks () {
		TW_Pages::walk(self::$htdocs);
		TW_Posts::walk(self::$htdocs);
	}
	
	private static function archives () {
		TW_DateArchive::walk(self::$htdocs);
		TW_TagArchive::walk(self::$htdocs);
		TW_Paginate::walk(self::$htdocs);
	}
	
	private static function urlify () {
		TW_Excerpt::walk(self::$htdocs);
		
		self::make(array(
			"pool",
			"permalinks",
			"archives"
		));
		
		TW_Markdown::walk(self::$htdocs);
		TW_UnParseContent::walk(self::$htdocs);
		TW_AddIndexFile::walk(self::$htdocs);
	}
	private static function display () {
		echo "\n" . print_r( self::$htdocs->data, 1 );
	}
	
	
	// read in the content folder, and make the 
	private static function read () {
		self::$content = FileTree::read("content");
		// error_log("content: " . self::$content . json_encode(self::$content->data));
		self::$htdocs = new ContentNode(self::$content->data);
		self::$htdocs->name = "htdocs";
		// error_log("after read: " . self::$htdocs . json_encode(self::$htdocs->data));
	}
	static function pruneUnchanged () {
		// remove any files from the tree that are older than their cached copy.
	}
	
	static function pruneOutDated () {
		// remove files in htdocs that are not in the cache.
	}
	
	static function writeCache ($which = "") {
		// write the file metadata to the cache
		if ($which !== "htdocs") FileTree::writeCache(self::$content);
		if ($which !== "cache") FileTree::writeCache(self::$htdocs);
	}
	
	static function write () {
		// call renderers (templates, markup, etc.)
		// write all the files from the data tree.
		Builder::make(array('writeCache'));
		// error_log("writing ===>> " . json_encode(self::$htdocs->data));
		FileTree::write(self::$htdocs);
	}
}
