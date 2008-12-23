<?php

// a class for querying all the nodes in a terse and expressive way

// TreeQuery::select(array(
// 	"fields" => array("name", array("sortorder", 0), "title", "permalink"),
// 	"orderby" => array("sortorder", "asc"),
// 	"where" => array("archive" => true, archivetype" => array("tag", "ifnotfound")),
// 	"from" => $this->root()
// ))

class TreeQuery {
	
	private static $CACHE = array();
	public static function select ($query, &$from, $flush = false) {
		$key = self::key($query, $from);
		
		$exists = self::idx(self::$CACHE, $key);
		if ($exists && !$flush) return $exists;
		
		return (self::$CACHE[$key] = self::queryStep(
			self::idx($query, "fields", array()),
			self::where(self::idx($query, "where")),
			self::orderby(self::idx($query, "orderby")),
			$from
		));
	}
	
	private static function key ($query, &$from) {
		return md5(json_encode(array($query, spl_object_hash($from))));
	}
	
	private static function queryStep ($fields, $where, $orderby, &$from) {
		if (empty($fields)) return array();
		
		$results = array();
		$children = array();
		
		// first look for matching children.
		foreach (
			$from->children as $child
		) $children = array_merge(
			$children, self::queryStep($fields, $where, $orderby, $child)
		);
		
		if ($orderby) usort($children, $orderby);
		if ($where($from)) {
			// parent matches. return array with one member, which has children.
			$r = self::getFields($fields, $from);
			$r['children'] =& $children;
			return array($r);
		}
		return $children;
	}
	
	private static function getFields ($fields, &$from) {
		$f = array();
		foreach (
			$fields as $field
		) if (
			is_string($field)
		) $f[$field] = $from->$field;
		elseif (
			is_array($field)
		) $f[$field[0]] = $from->header($field[0], $field[1]);
		return $f;
	}
	
	
	private static $WHERES = array();
	private static function where ($where) {
		if (!$where) $where = array();
		$key = json_encode($where);
		$exists = self::idx(self::$WHERES, $key, null);
		if ($exists) return $exists;
		$body = array('true');
		foreach ($where as $field => $value) {
			if (!is_array($value)) $value = array($value, null);
			list($field, $value, $default) = array(
				var_export($field, 1), var_export($value[0], 1), var_export($value[1], 1)
			);
			$body[] = "\$node->header($field, $default) === $value";
		}
		$body = implode(" && ", $body);
		
		return (self::$WHERES[$key] = create_function('&$node', "return ($body);"));
	}
	
	private static $ORDERBYS = array();
	private static function orderby ($orderby) {
		if (empty($orderby)) return null;
		$key = json_encode($orderby);
		$exists = self::idx(self::$ORDERBYS, $key, null);
		if ($exists) return $exists;
		
		// "orderby" => array("sortorder" => "asc", "title" => "asc"),
		/*
		if ($a['sortorder'] > $b['sortorder']) return 1;
		elseif ($a['sortorder'] < $b['sortorder']) return -1;
		elseif ($a['title'] > $b['title']) return 1;
		elseif ($a['title'] < $b['title']) return -1;
		else return 0;
		*/
		$body = array();
		foreach ($orderby as $field => $dir) {
			$comp = (trim(strtolower($dir)) === "asc") ? array(">", "<") : array("<", ">");
			$field = var_export($field, 1);
			$body[] = <<<BODY
if (\$a[$field] {$comp[0]} \$b[$field]) return 1;
BODY;
			$body[] = <<<BODY
if (\$a[$field] {$comp[1]} \$b[$field]) return -1;
BODY;

		}
		$body = implode("\nelse", $body) . "\nelse return 0;";
		// error_log($body);
		return (self::$ORDERBYS[$key] = create_function(
			'&$a,&$b', $body
		));
	}
	
	
	private static function idx ($arr, $idx, $else = null) {
		return array_key_exists($idx, $arr) ? $arr[$idx] : $else;
	}
	
}
