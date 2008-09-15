<?php

class TW_ArchiveSort extends TW_Base {
	protected static function each ($node) {
		if (
			!$node->header("archive") ||
			!is_array($node->content->body)
		) return;
		$posts = array();
		foreach ($node->content->body as $post) {
			while (
				array_key_exists($post->headers->date, $posts)
			) $post->headers->date ++;
			$posts[ $post->headers->date ] = $post;
		}
		if (krsort($posts, SORT_NUMERIC)) $node->content->body = $posts;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
