<body>
<div id="doc">
<?php

template("head.php");

template("body.php");

template("foot.php");


?></div>
<?php
echo "<pre>";
var_dump(str_replace("\t", "  ", htmlentities($node->content)));
echo "</pre>";
?>
</body>
</html>