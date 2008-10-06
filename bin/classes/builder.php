<?php

class Builder {
	
	private static $content = null;
	private static $htdocs = null;
	private static $config_done = false;
	
	public static function make ($argv = array()) {
		error_log("Builder::make " . implode(" ", $argv));
		$argv = Config::readArgs($argv);
		self::config();
		
		if (empty($argv)) {
			$argv = array('full');
		}
		if (is_string($argv)) {
			$argv = array($argv);
		}
		foreach ($argv as $arg) {
			if (method_exists("Builder", $arg)) {
				error_log("--> $arg");
				Builder::$arg();
			}
		}
	}
	
	private static function emptyDir ($dir) {
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
		}
		return @unlink($file);
	}
	
	private static function clean () {
		self::emptyDir("bin/cache");
		self::emptyDir(Config::get("output"));
	}
	private static function remove () {
		self::rm_recurse(Config::get("output"));
	}
	private static function full () {
		self::make(array(
			'remove',
			'read',
			'parse',
			'urlify',
			'write'
		));
	}
	private static function config () {
		
		if (self::$config_done) return;
		
		self::$config_done = true;
		
		// first, freeze anything up to this point, most likely from command line.
		Config::fixAll();
		
		// apply the default paths, just so there's something.
		$paths = array("content"=>"content", "output"=>"htdocs", "template"=>"tpl");
		Config::seta($paths);
		
		// now, load up the defaults in the ahyane basedir.
		if ( !Config::read('.ahyaneconfig') ) {
			trigger_error("Could not find .ahyaneconfig file in " . AHYANE_BASEDIR, E_USER_WARNING);
		}
		
		Config::read( Config::get("content") . '/.ahyaneconfig' );
		Config::read( Config::get("output") . '/.ahyaneconfig' );
		Config::read( Config::get("template") . '/.ahyaneconfig' );
		
		// now, for all three, make sure that it's a valid folder!
		foreach (
			$paths as $k => $v
		) if (
			!realpath( Config::get($k) )
		) if (
			!mkdir( Config::get($k) )
		) trigger_error(
			"Path does not exist, could not be created: " . Config::get($k), E_USER_ERROR
		);
		
		// nothing additional should be changed from now on.
		Config::fixAll();
	}
	private static function showConfig () {
		error_log( var_export(Config::getAll(), 1) );
	}
	
	private static function parse () {
		TW_ParseContent::walk(self::$htdocs);
	}
	
	private static function pool () {
		TW_Pool::walk(self::$htdocs);
	}
	
	private static function permalinks () {
		TW_Permalinks::walk(self::$htdocs);
	}
	
	private static function archives () {
		TW_HomePage::walk(self::$htdocs);
		TW_LinkPosts::walk(self::$htdocs);
		TW_DateArchive::walk(self::$htdocs);
		TW_TagArchive::walk(self::$htdocs);
		TW_Feed::walk(self::$htdocs);
		TW_ArchiveSort::walk(self::$htdocs);
		TW_Paginate::walk(self::$htdocs);
	}
	
	private static function urlify () {
		TW_Excerpt::walk(self::$htdocs);
		
		self::make(array(
			'pool',
			'permalinks',
			'archives'
		));
		
		TW_Markdown::walk(self::$htdocs);
		TW_ViewLayer::walk(self::$htdocs);
		TW_AddIndexFile::walk(self::$htdocs);
	}
	
	private static function display () {
		echo "\n" . print_r( self::$htdocs->data, 1 );
	}
	
	// read in the content folder, and make the 
	private static function read () {
		self::$content = FileTree::read(Config::get("content"));
		self::$htdocs = new ContentNode(self::$content->data);
		self::$htdocs->name = Config::get("output");
	}
	
	static function writeCache ($which = "") {
		// write the file metadata to the cache
		if ($which !== "htdocs") FileTree::writeCache(self::$content);
		if ($which !== "cache") FileTree::writeCache(self::$htdocs);
	}
	
	static function write () {
		FileTree::write(self::$htdocs);
	}
}
