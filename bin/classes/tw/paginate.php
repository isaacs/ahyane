<?php

class TW_Paginate extends TW_Base {
	protected static function each ($node) {
		if (
			!$node->header("archive")
		) return;
		
		// paginated feeds is silly.
		if ( $node->header("feed") ) {
			$node->content->body = array_slice($node->content->body, 0, Config::get("maxperpage"));
			return;
		}
		
		$posts = $node->content->body;
		$maxpages = ceil( count($posts) / Config::get("maxperpage") );
		
		$node->content->headers->pagecount = $maxpages;
		$node->content->body = null; // just so the data copy in the loop will be faster.
		$node->content->headers->page = 1;
		
		$previous = null;
		for ($i = 1; $i <= $maxpages; $i ++) {
			$p = $node->__(Config::get("pageprefix") . $i, true);
			$p->content = to_object($node->content);
			$p->content->headers->page = $i;
			$p->content->body = array_slice($posts, $i - 1, Config::get("maxperpage"));
			
			if ($previous) self::link($previous, $p);
			$previous = $p;
		}
		
		// now make the root node === page/1
		$node->content = $node->__(Config::get("pageprefix") . 1)->content;
	}
	
	protected static function link ($next, $previous) {
		$next->content->headers->previous = to_object(array(
			"href" => $previous->path,
			"title" => sprintf(Config::get("HigherPageText"), $previous->header("page"))
		));
		$previous->content->headers->next = to_object(array(
			"href" => $next->path,
			"title" => sprintf(Config::get("LowerPageText"), $next->header("page"))
		));
	}	
	
	public static function walk ($node) { parent::walk($node); }
}
