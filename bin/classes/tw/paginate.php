<?php

class TW_Paginate extends TW_Base {
	protected static function each ($node) {
		if (
			!$node->header("archive") ||
			!is_array($node->body)
		) return;
		
		// paginated feeds is silly.
		// just show the first page always.
		if ( $node->header("feed") ) {
			$node->body = array_slice($node->body, 0, Config::get("maxperpage"));
			return;
		}
		
		$posts = $node->body;
		$maxpages = ceil( count($posts) / Config::get("maxperpage") );
		
		$node->pagecount = $maxpages;
		$node->body = null; // just so the data copy in the loop will be faster.
		$node->page = 1;
		
		$previous = null;
		for ($i = 1; $i <= $maxpages; $i ++) {
			$p = $node->__(Config::get("pageprefix") . $i, true);
			
			$p->setHeader($node->headers);
			$p->page = $i;
			
			$p->body = array_slice(
				$posts, Config::get("maxperpage") * ($i - 1), Config::get("maxperpage")
			);
			
			if ($previous) self::link($previous, $p);
			$previous = $p;
		}
		
		// now make the root node === page-1
		$node->content = $node->__(Config::get("pageprefix") . 1)->content;
	}
	
	protected static function link ($next, $previous) {
		$next->previous = to_object(array(
			"href" => $previous->href,
			"title" => sprintf(Config::get("HigherPageText"), $previous->page)
		));
		$previous->next = to_object(array(
			"href" => $next->href,
			"title" => sprintf(Config::get("LowerPageText"), $next->page)
		));
	}	
	
	public static function walk ($node) { parent::walk($node); }
}
