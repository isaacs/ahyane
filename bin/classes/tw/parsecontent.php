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
		return $node->header("slug",
			preg_replace('~[^a-zA-Z0-9%]+~', '-',
				str_replace('%20', ' ', rawurlencode( strtolower( trim(self::getTitle($node)) ) ) )
			)
		);
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
		return array_map('trim', explode(",", $node->header("tags", "")));
	}
	
	private static function getHeaders ($node) {
		// get the slug
		$node->content->headers->title = self::getTitle($node);
		$node->content->headers->slug = self::getSlug($node);
		
		$node->content->headers->tags = self::getTags($node);
		
		$node->content->headers->date = strtotime($node->header("date"));

		$node->content->headers->type = self::getType($node);
		$node->content->headers->status = self::getStatus($node);
		$node->content->headers->original = $node->path;
		$node->name = $node->content->headers->slug;
		
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

