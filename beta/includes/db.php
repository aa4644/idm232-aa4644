<?php

// Use getenv() to retrieve values set in .htaccess or environment variables
$db_server = getenv('DB_SERVER');
$db_username = getenv('DB_USERNAME');
$db_password = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');

// Create a connection
$connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// echo "Connected successfully. MySQL version: " . mysqli_get_server_info($connection);

?>
