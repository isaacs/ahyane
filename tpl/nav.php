<?php
if ($node->header("previous") || $node->header("next")) {
	echo '<div class="nav">';
	if ($node->header("previous")) {
		echo '<a class="previous" href="'. $node->header("previous")->href . '">' .
			$node->header("previous")->title . '</a>';
	}
	if ($node->header("previous") && $node->header("next")) {
		echo '<span>|</span>';
	}
	if ($node->header("next")) {
		echo '<a class="next" href="'. $node->header("next")->href . '">' .
			$node->header("next")->title . '</a>';
	}
	echo '</div>';
}

