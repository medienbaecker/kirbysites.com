<?php
header('Content-type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?= $site->title()->xml() ?></title>
		<link><?= $site->url() ?></link>
		<description><?= $site->description()->xml() ?></description>
		<atom:link href="<?= $site->url() ?>/articles.xml" rel="self" type="application/rss+xml" />
		<language>en</language>
		<lastBuildDate><?= date('r') ?></lastBuildDate>
		<?php foreach ($pages->listed()->sortBy('date', 'desc') as $website): ?>

			<?php

			if ($website->date()->isEmpty()) continue;

			$images = '';

			foreach ($website->images()->sorted() as $image) {
				$images .= '<img src="' . $image->url() . '" alt="' . $image->alt()->or('Panel screenshot of ' . $website->shortUrl())->html() . '" />';
				if ($image->caption()->isNotEmpty()) {
					$images .= $image->caption()->kt();
				}
			}
			?>

			<item>
				<title><?= $website->title()->xml() ?></title>
				<link><?= $website->content()->url()->escape('xml') ?></link>
				<guid isPermaLink="true"><?= $website->url() ?></guid>
				<pubDate><?= $website->date()->toDate('r') ?></pubDate>
				<description>
					<![CDATA[<?= $images ?>]]>
				</description>
			</item>
		<?php endforeach ?>
	</channel>
</rss>