<?php

class TW_Paginate extends TW_Base {
	const PAGE_MAX = 5;
	const PAGE_PREFIX = "page";
	
	protected static function each ($node) {
		if (
			!$node->header("archive")
		) return;
		
		$posts = $node->content->body;
		$maxpages = ceil( count($posts) / self::PAGE_MAX );
		
		$node->content->headers->pagecount = $maxpages;
		$node->content->body = null; // just so the data copy in the loop will be faster.
		$node->content->headers->page = 1;
		
		for ($i = 1; $i <= $maxpages; $i ++) {
			$p = $node->__(self::PAGE_PREFIX . "/$i", true);
			$p->content = to_object($node->content);
			$p->content->headers->page = $i;
			$p->content->body = array_slice($posts, $i - 1, self::PAGE_MAX);
		}
		
		// now make the root node === page/1
		$node->content->body = $node->__("page/1")->content->body;
		
	}
	
	public static function walk ($node) { parent::walk($node); }
}
