<?php
require_once('./functions.php');
session_start();

header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

$id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($id <= 0) {
    echo json_encode([]);
    exit;
}

$get = "SELECT * FROM users WHERE id = $id ORDER BY id DESC";
$query = mysqli_query($db_connection, $get);

if (!$query) {
    echo json_encode([]);
    exit;
}

$rows = [];

while ($row = mysqli_fetch_assoc($query)) {
    $rows[] = $row;
}

echo json_encode($rows);
exit;