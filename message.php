<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

if (!isset($_SESSION['user']) || !isset($_GET['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];
$receiver_id = (int)$_GET['user'];

// Check if the receiving user exists in users table
$check = $conn->prepare("SELECT id FROM users WHERE id = ?");
$check->bind_param("i", $receiver_id);
$check->execute();
$result = $check->get_result();
if ($result->num_rows === 0) {
    echo "User does not exist.";
    exit();
}

// Handle sending message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $msg = htmlspecialchars(trim($_POST['message']));
    if (!empty($msg)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $receiver_id, $msg);
        $stmt->execute();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Chat - CO-OP</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #chat-box {
            height: 300px;
            overflow-y: scroll;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .chat-form {
            display: flex;
            gap: 10px;
        }

        .chat-form input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .chat-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .chat-form button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function loadMessages() {
            fetch("message_loader.php?user=<?php echo $receiver_id; ?>")
                .then(res => res.text())
                .then(data => {
                    const chatBox = document.getElementById("chat-box");
                    chatBox.innerHTML = data;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        document.addEventListener("DOMContentLoaded", () => {
            loadMessages();
            setInterval(loadMessages, 1000);
        });
    </script>
</head>

<body>
    <div class="container">
        <h2>Chat</h2>

        <div id="chat-box"></div>

        <form method="post" class="chat-form" autocomplete="off">
            <input type="text" name="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>

        <p style="margin-top: 15px;"><a href="home.php">Back to Home</a></p>
    </div>
</body>

</html>
