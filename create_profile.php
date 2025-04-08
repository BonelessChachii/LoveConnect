<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Check if a profile for the current user already exists
$stmt = $conn->prepare("SELECT id FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
$profile_exists = ($result->num_rows > 0);
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $age = (int)$_POST['age'];
    $hobbies = htmlspecialchars($_POST['hobbies']);
    $bio = htmlspecialchars($_POST['bio']);
    $favorite_games = htmlspecialchars($_POST['favorite_games']);

    $pic = "";
    if ($_FILES['profile_pic']['name']) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir);
        }
        $pic = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $pic);
    }

    if ($profile_exists) {
        // Update existing profile
        $stmt = $conn->prepare("UPDATE profiles SET name = ?, age = ?, hobbies = ?, bio = ?, favorite_games = ?, profile_pic = ? WHERE user_id = ?");
        $stmt->bind_param("sissssi", $name, $age, $hobbies, $bio, $favorite_games, $pic, $_SESSION['user']);
    } else {
        // Insert new profile record
        $stmt = $conn->prepare("INSERT INTO profiles (user_id, name, age, hobbies, bio, favorite_games, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isissss", $_SESSION['user'], $name, $age, $hobbies, $bio, $favorite_games, $pic);
    }
    $stmt->execute();
    $stmt->close();

    header("Location: home.php");
    exit();
} ?>
<!DOCTYPE html>
<html>

<head>
    <title>Create Profile - LoveConnect</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Create Your Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="number" name="age" placeholder="Age" required>
            <textarea name="hobbies" placeholder="Your Hobbies" required></textarea>
            <textarea name="bio" placeholder="Describe yourself" required></textarea>
            <textarea name="favorite_games" placeholder="Your Favorite Games" required></textarea>
            <input type="file" name="profile_pic" accept="image/*" required>
            <button type="submit">Save Profile</button>
        </form>
    </div>
</body>

</html>
