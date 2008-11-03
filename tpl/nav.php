<?php
if ($node->previous || $node->next) {
	echo '<div class="navigation">';
	if ($node->previous) {
		echo '<div class="alignleft"><a class="previous" href="'. $node->previous->href . '">' .
			$node->previous->title . '</a></div>';
	}
	if ($node->previous && $node->next) {
		echo '<span>|</span>';
	}
	if ($node->next) {
		echo '<div class="alignright"><a class="next" href="'. $node->next->href . '">' .
			$node->next->title . '</a></div>';
	}
	echo '</div>';
}
