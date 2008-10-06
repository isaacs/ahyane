<body>
<div id="doc">
<?php

$node->template("head.php");

$node->template("body.php");

$node->template("foot.php");


?></div>
<?php
echo "<pre>";
var_dump($node->content);
echo "</pre>";
?>
</body>
</html>