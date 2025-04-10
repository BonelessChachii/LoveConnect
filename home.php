<?php
session_start();
require 'db.php';

//for debugging like issue
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

// Initialize match index
if (!isset($_SESSION['match_index'])) {
    $_SESSION['match_index'] = 0;
}

// Handle Like/Pass

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['like']) || isset($_POST['pass'])) {
        $match_index = $_SESSION['match_index'];
        $matches = $_SESSION['matches_data'] ?? [];

        if (isset($matches[$match_index])) {
            $matched_user = $matches[$match_index];
            $receiver_id = $matched_user['user_id'];

            if (isset($_POST['like'])) {
                // Check if the like already exists to prevent duplicates
                $stmt = $conn->prepare("SELECT id FROM likes WHERE sender = ? AND receiver = ?");
                $stmt->bind_param("ii", $user_id, $receiver_id);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows == 0) {
                    $stmt->close();
                    // Insert the like into the likes table
                    $stmt = $conn->prepare("INSERT INTO likes (sender, receiver) VALUES (?, ?)");
                    $stmt->bind_param("ii", $user_id, $receiver_id);
                    $stmt->execute();
                }
                $stmt->close();
            }

            // Increment the match index in either case (like or pass)
            $_SESSION['match_index']++;
        }

        header("Location: home.php");
        exit();
    }
}

// Load fresh matches
$stmt = $conn->prepare("SELECT hobbies FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($my_hobbies);
$stmt->fetch();
$stmt->close();

$my_hobbies = explode(',', strtolower($my_hobbies));

$query = "SELECT user_id, name, age, bio, profile_pic, hobbies FROM profiles WHERE user_id != ?";
$results = $conn->prepare($query);
$results->bind_param("i", $user_id);
$results->execute();
$result_set = $results->get_result();
$matches = [];

while ($row = $result_set->fetch_assoc()) {
    $text = ($row['bio'] ?? '') . ' ' . ($row['hobbies'] ?? '');
    $other_hobbies = explode(',', strtolower($text));
    $match_score = count(array_intersect($my_hobbies, $other_hobbies));
    if ($match_score > 0) {
        $matches[] = $row;
    }
}

if (count($matches) === 0) {
    $random_query = $conn->prepare("SELECT user_id, name, age, bio, profile_pic FROM profiles WHERE user_id != ? ORDER BY RAND() LIMIT 5");
    $random_query->bind_param("i", $user_id);
    $random_query->execute();
    $matches = $random_query->get_result()->fetch_all(MYSQLI_ASSOC);
}

$_SESSION['matches_data'] = $matches;

$current_index = $_SESSION['match_index'];
$current_match = $matches[$current_index] ?? null;

// Notifications
$notif_check = $conn->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND seen = 0");
$notif_check->bind_param("i", $user_id);
$notif_check->execute();
$notif_check->bind_result($unseenCount);
$notif_check->fetch();
$notif_check->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home - CO-OP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- 🔔 Notification + Connections + Profile -->
    <div class="notification-bar">
        <button id="notif-btn" onclick="window.location.href='notifications.php';">
            Notifications
        </button>
        <button onclick="window.location.href='connections.php';">
            Connections
        </button>
    </div>

    <div class="profile-btn">
        <a href="edit_profile.php" class="btn">My Profile</a>
    </div>

    <!-- 🔥 Main Content -->
    <div class="container">
        <h2>Welcome to CO-OP</h2>

        <?php if ($current_match): ?>
            <div class="match-card">
                <img src="<?php echo $current_match['profile_pic']; ?>" alt="Profile Picture">
                <h3><?php echo htmlspecialchars($current_match['name']); ?> (<?php echo $current_match['age']; ?>)</h3>
                <p><?php echo htmlspecialchars($current_match['bio']); ?></p>

                <form method="post" class="swipe-buttons">
                    <button type="submit" name="like" class="like">❤️ Like</button>
                    <button type="submit" name="pass" class="pass">❌ Pass</button>
                </form>
            </div>
        <?php else: ?>
            <h3>Sorry, there's no more gamers left</h3>
            <a href="logout.php"> logout😢</a>
        <?php endif; ?>

    </div>

    <!-- 🔊 Notification sound & toast -->
    <audio id="notif-sound" src="Metal Gear Alert Sound Effect.mp3" preload="auto"></audio>
    <div class="toast" id="toast">📬 New message received!</div>

    <script>
        let lastCount = 0;

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        function checkNotifications() {
            fetch('check_notifications.php')
                .then(res => res.json())
                .then(data => {
                    const notifBtn = document.getElementById("notif-btn");
                    const newCount = data.count;

                    if (newCount > 0) {
                        notifBtn.innerText = `Notifications 🔔 (${newCount})`;
                        if (newCount > lastCount) {
                            document.getElementById("notif-sound").play();
                            showToast("📬 New message received!");
                        }
                    } else {
                        notifBtn.innerText = "Notifications";
                    }

                    lastCount = newCount;
                });
        }

        document.addEventListener("DOMContentLoaded", () => {
            checkNotifications();
            setInterval(checkNotifications, 5000);
        });
    </script>

</body>

</html>
