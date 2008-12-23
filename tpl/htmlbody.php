<body class="<?php
	echo $node->permalink ? 'widebody' : 'narrowbody';
?>" id="document<?php echo str_replace("/", '-', $node->path); ?>">
<div id="page">
<?php

$node->template("head.php");

$node->template("body.php");

$node->template("foot.php");


?></div>
<?php
echo "<!--\n";
var_dump($node->headers);
echo "\n-->";
?>
</body>
</html>