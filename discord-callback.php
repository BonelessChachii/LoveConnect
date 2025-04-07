<?php
session_start();
require 'db.php';

$client_id = '1357254475080794184';
$client_secret = 'UAg9FmWqVlpp020z7eAhgsZByurhOG3w';
$redirect_uri = 'http://localhost/LoveConnect/discord-callback.php';

if (!isset($_GET['code'])) {
    die("No code from Discord.");
}

$code = $_GET['code'];

$data = [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'scope' => 'identify email'
];

$ch = curl_init('https://discord.com/api/oauth2/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

$access_token = $response['access_token'] ?? null;

if (!$access_token) {
    die("Failed to get access token.");
}

$ch = curl_init('https://discord.com/api/users/@me');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $access_token"]);
$user = json_decode(curl_exec($ch), true);
curl_close($ch);

$discord_id = $user['id'];
$email = $user['email'];
$name = $user['username'] . '#' . $user['discriminator'];

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id);
    $stmt->fetch();
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, discord_id, password) VALUES (?, ?, ?, '')");
    $stmt->bind_param("sss", $name, $email, $discord_id);
    $stmt->execute();
    $user_id = $stmt->insert_id;
}
$stmt->close();

$_SESSION['user'] = $user_id;
// ✅ Step: Auto-create profile for Discord users if missing
$check_profile = $conn->prepare("SELECT id FROM profiles WHERE user_id = ?");
$check_profile->bind_param("i", $user_id);
$check_profile->execute();
$check_profile->store_result();

if ($check_profile->num_rows == 0) {
    $insert_profile = $conn->prepare("INSERT INTO profiles (user_id, name, age, bio, hobbies, favorite_games, profile_pic) VALUES (?, ?, NULL, '', '', '', NULL)");
    $insert_profile->bind_param("is", $user_id, $name);
    $insert_profile->execute();
    $insert_profile->close();
}

// Fetch the profile info for the logged-in user
$stmt = $conn->prepare("SELECT bio, hobbies, favorite_games FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

// Check if the profile info is empty
if (empty($profile['bio']) || empty($profile['hobbies']) || empty($profile['favorite_games'])) {
    // Redirect to the page where user can update their profile info
    header("Location: create_profile.php");
    exit();
} else {
    // If profile info is already filled out, redirect to home.php
    header("Location: home.php");
    exit();
}

exit();
