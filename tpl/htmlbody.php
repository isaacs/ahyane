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
echo "<div id=debug><pre>";
var_dump($node->headers);
echo "</pre></div>";
?>
</body>
</html>