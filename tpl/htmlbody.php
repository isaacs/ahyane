<body>
<div id="doc" class="yui-t6">
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