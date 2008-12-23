<ul>
	<?php
	foreach ($monthlies as $monthly) {
		echo "<li><a href=\"{$monthly['href']}\">" .
			date(Config::get("MonthArchiveTitleDatePart"), $monthly['archivestart']) . 
				"</a> ({$monthly['postcount']})</li>";
	}
	?>
</ul>