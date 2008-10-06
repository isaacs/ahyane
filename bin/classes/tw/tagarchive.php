<?php

class TW_TagArchive extends TW_Base {
	
	private static $tagsToSlugs;
	private static $postsByTag;
	
	protected static function finish ($node) {
		// now we have the full list of posts indexed by tags, and their slugs.
		// walk through it, putting excerpts at the right places.
		
		// now we have arrays of posts.
		// turn into new thing with lists of miniposts?
		foreach (self::$postsByTag as $tag => $list) if ($tag && count($list) > 0) {
			// $key is something like 2008/05/01
			// $list is an array of node references.
			
			$result = $node->__(Config::get("tagprefix") . self::$tagsToSlugs[$tag], true);
			$result->content = to_object(array(
				'headers' => array(
					'archive' => true,
					'archivetype' => 'tag',
					'tag' => $tag,
					'slug' => self::$tagsToSlugs[$tag]
				),
				'body' => array()
			));
			foreach (
				$list as $post
			) $result->content->body[] = to_object($post->content);
			
		}
	}
	protected static function start ($node) {
		self::$tagsToSlugs = array();
		self::$postsByTag = array();
	}
	
	private static function getTag ($tag) {
		if (
			array_key_exists($tag, self::$tagsToSlugs)
		) return $tag;
		
		$slug = str_replace(" ", "-", 
			strtolower(trim(preg_replace('~[^a-zA-Z0-9%]+~', ' ', $tag)))
		);
		if (!$slug) return;
		
		$i = 0;
		$s = $slug;
		while (
			in_array($slug, self::$tagsToSlugs)
		) $slug = $s . ($i++);
		self::$tagsToSlugs[ $tag ] = $slug;
		return $tag;
	}
	
	private static function addToTag ($tag, $node) {
		if (
			!array_key_exists($tag, self::$postsByTag)
		) self::$postsByTag[$tag] = array();
		self::$postsByTag[$tag][] = $node;
	}
	
	protected static function each ($node) {
		if (
			!$node->header("tags") ||
			!is_array($node->header("tags"))
		) return;
		foreach (
			$node->header("tags") as $tag
		) self::addToTag(self::getTag($tag), $node);
	}
	
	public static function walk ($node) { parent::walk($node); }
}
