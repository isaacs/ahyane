<?php

class TW_DateArchive extends TW_Base {
	
	private static $postsByDate = array();
	
	protected static function finish ($node) {
		// now we have the full list of posts indexed by date.
		// walk through it, putting excerpts at the right places.
		$excerpts = array(
			'year' => array(),
			'month' => array(),
			'day' => array()
		);
		
		foreach (self::$postsByDate as $date => $post) {
			foreach (array(
				'Y' => 'year',
				'Y/m' => 'month',
				'Y/m/d' => 'day'
			) as $format => $datetype) {
				$key = date($format, $date);
				if (
					!array_key_exists($key, $excerpts[$datetype])
				) $excerpts[$datetype][$key] = array();
				$excerpts[$datetype][$key][] = $post;
			}
		}
		
		// now we have arrays of posts.
		// turn into new thing with lists of miniposts?
		foreach ($excerpts as $kind => $lists) foreach ($lists as $key => $list) {
			// $key is something like 2008/05/01
			// $list is an array of node references.
			$result = $node->__($key, true);
			$result->content = to_object(array(
				'headers' => array(
					'archive' => true
				),
				'body' => array()
			));
			foreach (
				$list as $post
			) $result->content->body[] = to_object($post->content);
			
		}
	}
	protected static function start ($node) {
		self::$postsByDate = array();
	}
	protected static function each ($node) {
		if (
			!$node->header("date") ||
			!$node->header("permalink") ||
			$node->header("type") === "static"
		) return;
		while (
			array_key_exists($node->header("date"), self::$postsByDate)
		) $node->content->headers->date ++;
		self::$postsByDate[ $node->header("date") ] = $node;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
