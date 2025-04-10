<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

$stmt = $conn->prepare("
    SELECT users.id, users.name, COUNT(*) as message_count 
    FROM messages 
    JOIN users ON messages.sender_id = users.id 
    WHERE messages.receiver_id = ? AND messages.seen = 0 
    GROUP BY messages.sender_id
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .notif-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: 40px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .notif-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .chat-button {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .chat-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="notif-box">
        <h2>New Notifications</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notif-item">
                    <span><?php echo htmlspecialchars($row['name']); ?> (<?php echo $row['message_count']; ?>)</span>
                    <a class="chat-button" href="message.php?user=<?php echo $row['id']; ?>">Chat 💬</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No new notifications 📭</p>
        <?php endif; ?>
        <p style="text-align: center; margin-top: 20px;"><a href="home.php">← Back to Home</a></p>
    </div>

</body>

</html>
