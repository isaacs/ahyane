<?php

class TW_TagArchive extends TW_Base {
	
	private static $tagsToSlugs;
	private static $postsByTag;
	
	protected static function finish ($node) {
		// now we have the full list of posts indexed by tags, and their slugs.
		foreach (
			self::$postsByTag as $tag => $list
		) if (
			$tag && count($list) > 0
		) {

			$result = $node->__(Config::get("tagprefix") . self::$tagsToSlugs[$tag], true);
			$result->body = array();
			$result->setHeader(array(
				'archive' => true,
				'archivetype' => 'tag',
				'tag' => $tag,
				'slug' => self::$tagsToSlugs[$tag]
			));
			
			foreach (
				$list as $post
			) $result->body[] = new ContentNode($post->data);
		}
	}
	
	protected static function start ($node) {
		self::$tagsToSlugs = array();
		self::$postsByTag = array();
	}
	
	private static function getTag ($tag, $node) {
		if (
			array_key_exists($tag, self::$tagsToSlugs)
		) return $tag;
		
		$slug = slugify($tag) . '/';
		
		if (!$slug) return;
		
		$i = 0;
		$s = $slug;
		if (
			in_array($slug, self::$tagsToSlugs)
		) return array_search($slug, self::$tagsToSlugs);
		self::$tagsToSlugs[ $tag ] = $slug;
		return $tag;
	}
	
	private static function addToTag ($tag, $node, $numericindex) {
		if (
			!array_key_exists($tag, self::$postsByTag)
		) self::$postsByTag[$tag] = array();
		self::$postsByTag[$tag][] = $node;
		
		// NB: You can't use the [] operator on things with special setter/getters
		$tags = $node->tags;
		unset($tags[$numericindex]);
		$tags[
			Config::get("tagprefix") . self::$tagsToSlugs[$tag]
		] = $tag;
		$node->tags = $tags;
	}
	
	protected static function each ($node) {
		if (
			!$node->tags || !is_array($node->tags)
		) return;
		foreach (
			$node->tags as $i => $tag
		) self::addToTag(self::getTag($tag, $node), $node, $i);
	}
	
	public static function walk ($node) { parent::walk($node); }
}
