<?php

require_once('./functions.php');

header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode([]);
    exit;
}

$id = (int) $_GET['user_id'];

$get = "SELECT * FROM users WHERE id = $id";
$query = mysqli_query($db_connection, $get);

if (!$query) {
    echo json_encode([
        'error' => mysqli_error($db_connection)
    ]);
    exit;
}

$rows = [];

while ($row = mysqli_fetch_assoc($query)) {
    $rows[] = $row;
}

echo json_encode($rows);
exit;