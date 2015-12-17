<?php
// Get the YOURLS stuff.
require_once(dirname(__FILE__).'/includes/load-yourls.php');

// Connect to the database.
$db = yourls_db_connect();

// Get the Short URLs
if ($db) {
	$urls = $db->get_results('SELECT `keyword`, `url`, `title`, `timestamp`, `clicks` FROM `' . YOURLS_DB_TABLE_URL . '` ORDER BY `timestamp` DESC LIMIT 20');
}

// Encode the array to json
echo json_encode($urls);
?>