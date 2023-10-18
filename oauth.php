<?php
session_start();
require __DIR__ . '/session-variables.php';
setKeys();

// Define your Discord OAuth2 application credentials
$clientId = $_SESSION['oauth_id'];
$clientSecret = $_SESSION['oauth_secret'];
$redirectUri = $_SESSION['oauth_redirect'];

// Check if the user is initiating the OAuth flow
if (!isset($_GET['code'])) {
    // Redirect the user to Discord's authorization URL
    $discordAuthUrl = "https://discord.com/api/oauth2/authorize?client_id=$clientId&redirect_uri=$redirectUri&response_type=code&scope=identify%20guilds";
    header("Location: $discordAuthUrl");
    exit();
}

// Handle the callback from Discord
$discordCode = $_GET['code'];

// Exchange the code for an access token
$tokenUrl = "https://discord.com/api/oauth2/token";
$tokenData = [
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'grant_type' => 'authorization_code',
    'code' => $discordCode,
    'redirect_uri' => $redirectUri,
    'scope' => 'identify'
];

$tokenOptions = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($tokenData)
    ]
];

$tokenContext = stream_context_create($tokenOptions);
$tokenResponse = file_get_contents($tokenUrl, false, $tokenContext);
$tokenData = json_decode($tokenResponse, true);

if (isset($tokenData['access_token'])) {
    // Access token obtained successfully
    $accessToken = $tokenData['access_token'];
    $_SESSION['access_token'] = $accessToken;
    
    // Fetch user data from Discord
    $userDataUrl = "https://discord.com/api/users/@me";
    $userDataOptions = [
        'http' => [
            'header' => "Authorization: Bearer $accessToken"
        ]
    ];

    $userDataContext = stream_context_create($userDataOptions);
    $userDataResponse = file_get_contents($userDataUrl, false, $userDataContext);
    $userData = json_decode($userDataResponse, true);
    
    $_SESSION['discord_user_data'] = $userData;

    if (isset($_SESSION['discord_user_data'])) {
        // User data obtained successfully
        
        $_SESSION['logged_in'] = true;
        header("Location: process-login.php");
    } else {
        echo "Failed to fetch user data from Discord.";
    }
} else {
    echo "Failed to obtain access token from Discord.";
}
?>