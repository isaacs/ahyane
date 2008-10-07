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
				Config::get("yearArchive") => 'year',
				Config::get("monthArchive") => 'month',
				Config::get("dayArchive") => 'day'
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
					'archive' => true,
					'archivetype' => $kind,
					'archivekey' => Config::get("${kind}archive"),
					'archivestart' => $list[0]->content->headers->date,
					'archiveend' => $list[ count($list) - 1 ]->content->headers->date
				),
				'body' => array()
			));
			foreach (
				$list as $post
			) $result->content->body[] = self::copyContent($post->content);
			
		}
	}
	private static function copyContent ($content) {
		$n = new stdClass();
		$n->body = $content->body;
		$n->headers = $content->headers;
		$n->excerpt = $content->excerpt;
		return $n;
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
		// TODO: Remove this! Dates already unique-ifed in TW_Pool
		while (
			array_key_exists($node->header("date"), self::$postsByDate)
		) $node->content->headers->date ++;
		self::$postsByDate[ $node->header("date") ] = $node;
	}
	
	public static function walk ($node) { parent::walk($node); }
}
