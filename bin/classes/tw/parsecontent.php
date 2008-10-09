<?php

class TW_ParseContent extends TW_Base {
	// protected static function finish ($node) { die(); }
	
	private static function getTitle ($node) {
		// title is either the explicit title or the "name" with any .ext stripped off.
		return $node->header("title",
			mb_convert_case(
				str_replace('-', ' ', to_object(
					pathinfo($node->name)
				)->filename), MB_CASE_TITLE, 'UTF-8'
			)
		);
	}
	private static function getSlug ($node) {
		return $node->header("slug", $node->slugify(self::getTitle($node)));
	}
	private static function getType ($node) {
		return $node->header("type",
			( $node->header("date") ? 'blog' : 'static' )
		);
	}
	private static function getStatus ($node) {
		return $node->header("status", 'published');
	}
	
	private static function getTags ($node) {
		$tags = array();
		foreach (
			array_map('trim', explode(",", $node->header("tags", ""))) as $t
		) if ($t) $tags[] = $t;
		return $tags;
	}
	
	private static function getDate ($node) {
		$d = strtotime($node->header("date"));
		if (!$d) return $d;
		if (date("His", $d) !== '000000') return $d;
		$offset = explode(":", date("H:i:s", strtotime(Config::get("DefaultTime"))));
		return $d + ( $offset[0] * 3600 + $offset[1] * 60 + $offset[2] );
	}
	
	private static function getHeaders ($node) {
		// get the slug
		$node->content->headers->title = self::getTitle($node);
		$node->content->headers->slug = self::getSlug($node);
		
		$node->content->headers->tags = self::getTags($node);
		
		$node->content->headers->date = self::getDate($node);
		$node->content->headers->type = self::getType($node);
		$node->content->headers->status = self::getStatus($node);
		$node->content->headers->original = $node->path;
		
		$node->content->headers->modified = (
			$filename = realpath(
				AHYANE_BASEDIR . '/content' . $node->content->headers->original
			)
		) ? filemtime($filename) : 0;
		if (
			$node->content->headers->modified > @Config::get("LastModified")
		) Config::set("LastModified", $node->content->headers->modified);
		
		if (
			!property_exists($node->content->headers, "author")
		) $node->content->headers->author = Config::get("DefaultAuthor");
		
		return $node->content->headers;
	}
	
	protected static function each ($node) {
		$node->content = Parser::parse($node->content);
		
		// while we're at it, get some handy stuff.
		$node->content->headers = self::getHeaders($node);
		
		// error_log("Parsing walker: ". $node->path);
		
	}
	public static function walk ($node) { parent::walk($node); }
}

