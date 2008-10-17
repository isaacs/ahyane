<?php echo '<' . '?xml' ?> version="1.0" encoding="UTF-8"?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	>

<channel>
	<title><?php echo Config::get('SiteName') ?></title>
	<atom:link href="<?php echo $node->href ?>" rel="self" type="application/rss+xml" />
	<link><?php echo Config::get('SiteURL'); ?></link>
	<description><?php echo Config::get('SiteDescription') ?></description>
	<pubDate><?php echo date('r', Config::get("LastModified")) ?></pubDate>

	<generator>http://ahyane.com/</generator>
	<language><?php echo Config::get('SiteLanguage') ?></language>
	<?php
	foreach ($node->body as $post) {
		?>
		<item>
			<title><?php echo $post->headers->title ?></title>
			<link><?php echo $post->headers->permalink ?></link>
			<!-- <comments>TODO: DISQUS URL</comments> -->

			<pubDate><?php echo date('r', $post->headers->date); ?></pubDate>
			<dc:creator><?php echo $post->headers->author ?></dc:creator>
			
			<?php
			foreach ($post->headers->tags as $tag) {
				?>
				<category><![CDATA[<?php echo $tag ?>]]></category>
				<?php
			}
			?>
			
			<guid isPermaLink="true"><?php echo $post->headers->permalink ?></guid>
			<description><![CDATA[<?php echo $post->excerpt . '<a rel="bookmark" class="more" href="' .
				$post->headers->permalink . '">' . sprintf(
					Config::get("ReadMoreText"), $post->headers->title
				) . '</a>';
			?>]]></description>

				<content:encoded><![CDATA[<?php echo $post->body ?>]]></content:encoded>
				<!-- <wfw:commentRss>TODO: DISQUS FEED</wfw:commentRss> -->
			</item>
			<?php
		}
		?>
	</channel>
</rss>

