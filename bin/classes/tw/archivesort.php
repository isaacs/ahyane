<?php

class TW_ArchiveSort extends TW_Base {
	protected static function each ($node) {
		if (
			!$node->archive ||
			!is_array($node->body) ||
			count($node->body) <= 1
		) return;
		$posts = array();
		foreach ( $node->body as $post ) {
			if (!$post->date) $post->date = 0;
			while (
				array_key_exists($post->date, $posts)
			) $post->date ++;
			
			$posts[ $post->date ] = $post;
		}
		$node->postcount = count($posts);
		if (krsort($posts, SORT_NUMERIC)) $node->body = $posts;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
