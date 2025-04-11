<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || !isset($_GET['user'])) {
    exit();
}

$user_id = $_SESSION['user'];
$receiver_id = (int)$_GET['user'];

$stmt = $conn->prepare("
    SELECT sender_id, message, created_at 
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?) 
    ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $user_id, $receiver_id, $receiver_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $align = $row['sender_id'] == $user_id ? 'right' : 'left';
    $bgColor = $row['sender_id'] == $user_id ? '#d1f7c4' : '#ffffff';
    $time = date("h:i A", strtotime($row['created_at']));

    echo "
    <div style='text-align: {$align}; margin: 8px 0;'>
        <div style='
            display: inline-block;
            background-color: {$bgColor};
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 70%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        '>
            <div style='font-size: 14px;'>{$row['message']}</div>
            <div style='font-size: 10px; color: #888; margin-top: 4px; text-align: {$align};'>{$time}</div>
        </div>
    </div>";
}

// Mark messages as seen
$update = $conn->prepare("UPDATE messages SET seen = 1 WHERE sender_id = ? AND receiver_id = ? AND seen = 0");
$update->bind_param("ii", $receiver_id, $user_id);
$update->execute();
