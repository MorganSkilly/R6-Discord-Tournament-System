<?php
session_start();
require __DIR__ . '/session-variables.php';
setDiscordOauthKeys();

// Define your Discord OAuth2 application credentials
$clientId = $_SESSION['oauth_id'];
$clientSecret = $_SESSION['oauth_secret'];
$redirectUri = $_SESSION['oauth_redirect'];
session_destroy();

$discordAuthUrl = "https://discord.com/oauth2/authorize?client_id=$clientId&scope=bot&permissions=8";
header("Location: $discordAuthUrl");
exit();
