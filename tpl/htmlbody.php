<body>
<div id="doc">
<?php

$node->template("head.php");

$node->template("body.php");

$node->template("foot.php");


?></div>
<?php
echo "<pre>";
var_dump(str_replace("\t", "  ", htmlentities(print_r($node->content, 1))));
echo "</pre>";
?>
</body>
</html>