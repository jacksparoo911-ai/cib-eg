<?php
require_once('./functions.php');
require_once('../vendor/autoload.php');

session_start();

$id = $_GET['user_id'];
$status = $_GET['status'];
$url = $_GET['url'];

// Update status and url in DB
if ($status === '0' || $status === 0) {
    $query = 'UPDATE users SET url = ? WHERE id = ?';
    $stmt = $db_connection->prepare($query);
    $stmt->bind_param('si', $url, $id);
} else {
    $query = 'UPDATE users SET status = ?, url = ? WHERE id = ?';
    $stmt = $db_connection->prepare($query);
    $stmt->bind_param('ssi', $status, $url, $id);
}

if ($stmt->execute()) {

    // Trigger Pusher to notify the user's waiting page
    $options = array(
        'cluster' => 'ap2',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        'f8499dfcc5db13fb4153',
        '212f55cd103ef936b3f3',
        '2151243',
        $options
    );

    $dataUser = [
        'userId' => $id,
        'status' => $status,
        'url' => $url
    ];

    $pusher->trigger('my-channel-cib', 'admin-decision', $dataUser);

    echo "success";
} else {
    echo "error: " . $stmt->error;
}

$stmt->close();