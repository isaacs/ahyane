<?php

class TW_Paginate extends TW_Base {
	protected static function each ($node) {
		if (
			!$node->archive ||
			!is_array($node->body)
		) return;
		
		// paginated feeds is silly.
		// just show the first page always.
		if ( $node->feed ) {
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
		$node->body = $node->__(Config::get("pageprefix") . 1)->body;
		$node->setHeader($node->__(Config::get("pageprefix") . 1)->headers);
		$node->excerpt = $node->__(Config::get("pageprefix") . 1)->excerpt;
		
		self::link($node, $node->__(Config::get("pageprefix") . 2));
		
		$node->page = 0;
	}
	
	protected static function link ($next, $previous) {
		if (!$next || !$previous) return;
		
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
