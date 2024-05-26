<?php

$servername = getenv('DB_HOST');
$db_username = getenv('DB_USER');
$db_password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// Create connection
$db = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($db->connect_error) {
  die("Read connection failed: " . $db->connect_error);
}
