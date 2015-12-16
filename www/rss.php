<?php
// Get the YOURLS stuff.
require_once(dirname(__FILE__) . '/includes/load-yourls.php');

// Get the Short URLs
$items = yourls_api_stats('last', 20);

// Let make sure we are serving the right type.
header('Content-Type: application/atom+xml');

// I'm not sure what's going on here but if removed everything gose nuts.
echo '<?' . 'xml version="1.0" encoding="UTF-8" ?>';
?>

<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>Latest links on <?php echo YOURLS_SITE; ?></title>
		<link><?php echo YOURLS_SITE; ?></link>
		<description>Latest links on <?php echo YOURLS_SITE; ?></description>
		<atom:link href="<?php echo YOURLS_SITE; ?>/rss.php" rel="self" type="application/rss+xml" />
		<generator>YOURLS v<?php echo YOURLS_VERSION; ?></generator>
		<language>en</language>
		<?php foreach ($items['links'] as $item): ?>
		
		<item>
			<title><?php echo yourls_esc_html( $item['title'] ); ?></title>
			<description><?php echo htmlentities( $item['url'] ); ?></description>
			<pubDate><?php echo date('D, d M Y H:i:s O', strtotime($item['timestamp']) ); ?></pubDate>
			<link><?php echo $item['shorturl']; ?></link>
			<guid isPermaLink="false"><?php echo $item['shorturl']; ?></guid>
		</item>
		<?php endforeach ?>
		
	</channel>
</rss>
