<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $age = (int)$_POST['age'];
    $hobbies = htmlspecialchars($_POST['hobbies']);
    $bio = htmlspecialchars($_POST['bio']);

    $pic = "";
    if ($_FILES['profile_pic']['name']) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir);
        }
        $pic = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $pic);
    }

    // Update the existing profile instead of inserting a new one
    $stmt = $conn->prepare("UPDATE profiles SET name = ?, age = ?, hobbies = ?, bio = ?, profile_pic = ? WHERE user_id = ?");
    $stmt->bind_param("sisssi", $name, $age, $hobbies, $bio, $pic, $_SESSION['user']);
    $stmt->execute();

    header("Location: home.php");
    exit();
}
?>
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
            <input type="file" name="profile_pic" accept="image/*" required>
            <button type="submit">Save Profile</button>
        </form>
    </div>
</body>

</html>
