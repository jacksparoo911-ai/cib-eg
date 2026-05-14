<?php

require_once 'config.php';

// Application Name
$app_name = 'airlines';

// Database Connection
$db_connection = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME,
    DB_PORT
);

// Check Connection
if (!$db_connection) {
    die("Connection failed: " . mysqli_connect_error());
}

?>