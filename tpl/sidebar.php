<!-- Todo: cache pieces of this somehow?
	Looping through pages, category, archives every time seems mega dumb. -->
<div id="sidebar">
	<ul>
		<!--
			TODO: Search form!
		<li>
			<div>
				<form id="searchform" name="searchform" method="get" action="/?s=">
					<input type="text" id="livesearch" name="s" value="search this site">
					<input type="submit" id="searchsubmit" style="display: none;" value="Search">
				</form>
			</div>
		</li> -->
		<li>
			<p>
				<?php
				if (
					$node->archivetype === 'tag'
				) echo 'You are currently browsing the archives for the ' . $node->tag . ' category.';
				elseif (
					$node->archivetype === 'day'
				) echo 'You are currently browsing the <a href="${SiteURL}">${SiteName}</a> blog archives
					for the day ' . date(Config::get('DayArchiveTitleDatePart'), $node->archivestart) . '.';
				elseif (
					$node->archivetype === 'month'
				) echo 'You are currently browsing the <a href="${SiteURL}">${SiteName}</a> blog archives
					for ' . date(Config::get('MonthArchiveTitleDatePart'), $node->archivestart) . '.';
				elseif (
					$node->archivetype === 'year'
				) echo 'You are currently browsing the <a href="${SiteURL}">${SiteName}</a> blog archives
					for ' . date(Config::get('YearArchiveTitleDatePart'), $node->archivestart) . '.';
				elseif (
					$node->page && $node->page > 1
				) echo 'You are currently browsing the <a href="${SiteURL}">${SiteName}</a> archives.';
				?>
			</p>
		</li>
		<li class="pagenav">
			<h2>
				Pages
			</h2>
			<?php
			$node->template('sidebar_pagelist.php', array("pages" => $node->getPages()));
			?>
		</li>
		<li>
			<h2>
				Archives
			</h2>
			<?php
			$node->template('sidebar_monthlies.php', array("monthlies" => $node->getArchives("month")));
			?>
		</li>
		<li class="categories">
			<h2>
				Categories
			</h2>
			<?php
			$node->template('sidebar_tags.php', array('tags' => $node->getArchives("tag")));
			?>
		</li>
		<li>
			<h2>
				Meta
			</h2>
			<ul>
				<li>
					<a href="http://validator.w3.org/check/referer" title="This page validates as HTML 4.0 Strict">Valid <abbr title="HyperText Markup Language">HTML</abbr></a>
				</li>
				<li>
					<a href="http://ahyane.com/" title="Powered by Ahyane, nifty publishing platform.">Ahyane</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
<hr>
