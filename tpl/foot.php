

<hr>
<div id="footer">
	<p>
		<a href='http://ahyane.com/' rel='generator'>Built using Ahyane</a>
		<br><a href="${SiteUrl}/feed/">Entries (RSS)</a>	</p>
</div>

<script type="text/javascript">
(function() {
	var links = document.getElementsByTagName('a');
	var query = '?';
	for(var i = 0; i < links.length; i++) {
		if(links[i].href.indexOf('#disqus_thread') >= 0) {
			query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
		}
	}
	
	// document.getElementsByTagName('head')[0].appendChild((function () {
	// 	var s = document.createElement('script');
	// 	s.setAttribute("type", "text/javascript");
	//   s.setAttribute("charset", "utf-8");
	//   s.setAttribute("src", 'http://disqus.com/forums/ahyane/get_num_replies.js' + query);
	// 	return s;
	// })());
			document.write('<script type="text/javascript" src="http://disqus.com/forums/ahyane/get_num_replies.js' + query + '"></' + 'script>');
})();
</script>
