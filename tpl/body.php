<div id="content" class="<?php
	echo $node->permalink ? 'widecolumn' : 'narrowcolumn';
?>">

<h2 class="pagetitle"><?php echo $node->title; ?></h2>

<?php
$node->template("nav.php");

if (
	$node->permalink
) {
	$node->template("permalink.php");
	echo '</div>';
} else {
	$node->template("archive.php");
	echo '</div>';
	$node->template("nav.php");
	$node->template("sidebar.php");
}


