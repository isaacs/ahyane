<?php
$node->template("postbody.php");
$node->template("nav.php");
if (!$node->home) $node->template("comment.php");

