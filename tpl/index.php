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


echo "<pre>";
var_dump($node->content);
echo "</pre>";
?>
</body>
</html>