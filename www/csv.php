<?php
// Get the YOURLS stuff.
require_once(dirname(__FILE__).'/includes/load-yourls.php');

// Connect to the database.
$db = yourls_db_connect();

// Let make sure we are serving the right type.
header('Content-Type: text/csv; charset=utf-8');

// The file should be a download.
header('Content-Disposition: attachment; filename=mylbe-data.csv');

// Create the file pointer connected to the output stream
$csv_file = fopen('php://output', 'w');

// Output hte column headings
fputcsv($csv_file, array('Short Code', 'URL', 'Title', 'Timestamp', 'Clicks'));

// Get the Short URLs
if ($db) {
	$urls = $db->get_results('SELECT `keyword`, `url`, `title`, `timestamp`, `clicks` FROM `' . YOURLS_DB_TABLE_URL . '` ORDER BY `timestamp` DESC LIMIT 20');
}

foreach ($url as $urls) {
    fputcsv($csv_file, $url);
}
?>