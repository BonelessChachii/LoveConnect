<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user'];
$stmt = $conn->prepare("SELECT name, age, gender, interests FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($name, $age, $gender, $interests);
$stmt->fetch();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Your Profile - CO-OP</title>
</head>

<body>
    <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
    <p><strong>Age:</strong> <?php echo $age; ?></p>
    <p><strong>Gender:</strong> <?php echo $gender; ?></p>
    <p><strong>Interests:</strong> <?php echo htmlspecialchars($interests); ?></p>
    <a href="logout.php">Logout</a>
</body>

</html>
