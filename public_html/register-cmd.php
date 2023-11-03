<?php
session_start();
require __DIR__ . '/session-variables.php';
setDiscordOauthKeys();

$token = $_SESSION['bot_token'];
$app_Id = $_SESSION['app_id'];

$command = [
    'name' => 'test',
    'description' => 'test',
    'type' => 1, // 1 for slash commands
];

$ch = curl_init("https://discord.com/api/v10/applications/{$app_Id}/commands");

$headers = [
    "Authorization: Bot {$token}",
    "Content-Type: application/json",
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($command));

$response = curl_exec($ch);

if ($response === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Global slash command registered successfully: ' . $response;
}

curl_close($ch);
session_destroy();
?>
In this script, we're sending a POST request to the Discord API to register a global slash command. Make sure to replace 'YOUR_BOT_TOKEN', 'your_command_name', and 'Your command description' with your bot's token and the command details.

Just like in the previous examples, this is a simplified script for educational purposes. In a production environment, you should implement proper error handling, security measures, and validation. Ensure that your bot has the necessary permissions and that the application you're using to create the global command has a "bot" scope.





