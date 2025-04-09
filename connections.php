<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

// Fetch distinct users the current user has chatted with (sent or received)
$query = "
    SELECT DISTINCT u.id, p.name, p.profile_pic
    FROM users u
    INNER JOIN profiles p ON u.id = p.user_id
    WHERE u.id IN (
        SELECT DISTINCT CASE
            WHEN sender_id = ? THEN receiver_id
            WHEN receiver_id = ? THEN sender_id
        END
        FROM messages
        WHERE sender_id = ? OR receiver_id = ?
    ) AND u.id != ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiiii", $user_id, $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Your Connections - CO-OP</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .connection {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .connection img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 15px;
        }

        .connection-info {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .connection-name {
            font-weight: bold;
            color: #333;
        }

        .chat-btn {
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .chat-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Your Connections</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="connection">
                    <div class="connection-info">
                        <img src="<?php echo $row['profile_pic']; ?>" alt="Profile Pic">
                        <span class="connection-name"><?php echo htmlspecialchars($row['name']); ?></span>
                    </div>
                    <a href="message.php?user=<?php echo $row['id']; ?>" class="chat-btn">Chat 💬</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have no connections yet.</p>
        <?php endif; ?>

        <p style="margin-top: 20px;"><a href="home.php">← Back to Home</a></p>
    </div>

</body>

</html>
