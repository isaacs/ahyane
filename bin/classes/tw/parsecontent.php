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
		return $node->header("slug", slugify(self::getTitle($node)));
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
	
	private static function setHeaders ($node) {
		$node->setHeader(array(
			'title' => self::getTitle($node),
			'slug' => self::getSlug($node),
			'tags' => self::getTags($node),
			'date' => self::getDate($node),
			'type' => self::getType($node),
			'status' => self::getStatus($node),
			'original' => $node->path,
			'author' => $node->header("author", Config::get("DefaultAuthor"))
		));
		
		if (
			$node->modified > @Config::get("LastModified")
		) Config::set("LastModified", $node->modified);
	}
	
	protected static function each ($node) {
		$node->content = Parser::parse($node->body);
		
		// while we're at it, set some handy stuff.
		self::setHeaders($node);
				
	}
	public static function walk ($node) { parent::walk($node); }
}

