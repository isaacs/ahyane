<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Ahyane - <?php
	echo $node->header("title", "erm... something?");
	?></title>
</head>
<body>
	
	<h1><?php echo $node->header("title", "erm... something?"); ?></h1>
<?php

// if ($node->header("archive")) {
// 	// this is where you'd loop through the posts or something?
// 	foreach ($node->content->body as $piece) {
// 		echo "<h2>" . $piece->headers->title . "</h2>";
// 		echo "<h3>" . $piece->headers->permalink . "</h3>";
// 		echo "<pre>" . $piece->excerpt . "</pre>";
// 	}
// } else {
// 	echo $node->content->body;
// }

echo "<pre>";
var_dump($node->content);
echo "</pre>";
?>
</body>
</html>