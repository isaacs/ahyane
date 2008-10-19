<?php
if ($node->previous || $node->next) {
	echo '<div class="nav">';
	if ($node->previous) {
		echo '<a class="previous" href="'. $node->previous->href . '">' .
			$node->previous->title . '</a>';
	}
	if ($node->previous && $node->next) {
		echo '<span>|</span>';
	}
	if ($node->next) {
		echo '<a class="next" href="'. $node->next->href . '">' .
			$node->next->title . '</a>';
	}
	echo '</div>';
}

