<body>
<div id="doc">
<?php

$node->template("head.php");

$node->template("body.php");

$node->template("foot.php");


?></div>
<?php
echo "<pre>";
var_dump(str_replace("\t", "  ", htmlentities($node->content)));
echo "</pre>";
?>
</body>
</html>