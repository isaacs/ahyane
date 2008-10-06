<?php
class TW_LinkPosts extends TW_Base {
	private static $posts = array();
	
	protected static function finish ($node) {
		if (!ksort(self::$posts)) return;
		$prev = null;
		foreach (self::$posts as $post) {
			if ( $prev ) self::link($post, $prev);
			$prev = $post;
		}
	}
	
	protected static function linkTo ($post) {
		return to_object(array(
			"href" => $post->header("permalink"),
			"title" => $post->header("title", Config::get("DefaultTitle"));
		));
	}
	
	protected static function link ($next, $previous) {
		$prev->content->headers->next = self::linkTo($next);
		$next->content->headers->previous = self::linkTo($previous);
	}
	
	protected static function each ($node) {
		if (
			$node->header("type") !== "blog" ||
			!$node->header("permalink")
		) continue;
		self::$posts[$node->header("date")] = $node;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
