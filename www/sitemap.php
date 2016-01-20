<?php
// Get the YOURLS stuff.
require_once(dirname(__FILE__) . '/includes/load-yourls.php');

// Connect to the database.
$db = yourls_db_connect();

// Get the Short URLs
if ($db) {
	$urls = $db->get_results('SELECT `url`, `timestamp` FROM `' . YOURLS_DB_TABLE_URL . '` WHERE `url` LIKE "%://files.myl.be/%"');
}

// Let make sure we are serving the right type.
header('Content-Type: application/xml');

// I'm not sure what's going on here but if removed everything gose nuts.
echo '<?' . 'xml version="1.0" encoding="UTF-8" ?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $short): ?>
    <url>
        <loc><?php echo $short->url; ?></loc>
        <lastmod><?php echo $short->timestamp; ?></lastmod>
    </url>
<?php endforeach ?>
</urlset>
