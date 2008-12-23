<li>
	<form name="searchform" id="searchform" method="get" action="http://www.google.com/search">
		<div>
			<input type="hidden" name="client" value="ahyane">
			<label style="position:absolute;top:-99em;left:-99em" for="sidebar_q">Search this site</label>
			<input type="text" id="sidebar_q" name="q" value="search this site" onfocus="if(this.value==='search this site')this.value=''" onblur="if(this.value==='')this.value='search this site'">
			<input type=submit style="position:absolute;top:-99em;left:-99em" value="Search">
			<input type="hidden" name=q value="site:${SiteURL}">
		</div>
	</form>
</li>
