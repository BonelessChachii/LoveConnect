<?php
// Discord OAuth2 login redirect
$client_id = '1357254475080794184'; // client ID
$redirect_uri = urlencode('http://localhost/LoveConnect/discord-callback.php');
$scope = 'identify email';
$response_type = 'code';

$auth_url = "https://discord.com/api/oauth2/authorize?client_id=$client_id&redirect_uri=$redirect_uri&response_type=$response_type&scope=$scope";

header("Location: $auth_url");
exit();
