<?php
session_start();

if(!$_SESSION['logged_in']){
  header('Location: error.php?error=notloggedin');
  exit();
}

function returnGuilds()
{
  if (isset($_SESSION['access_token'])) {
    // Access token obtained successfully
    $accessToken = $_SESSION['access_token'];
    
    // Fetch user data from Discord
    $userDataUrl = "https://discord.com/api/users/@me/guilds";
    $userDataOptions = [
        'http' => [
            'header' => "Authorization: Bearer $accessToken"
        ]
    ];

    $userDataContext = stream_context_create($userDataOptions);
    $userDataResponse = file_get_contents($userDataUrl, false, $userDataContext);
    $userData = json_decode($userDataResponse, true);

    if (isset($userData)) {
        // User data obtained successfully

        if (!empty($userData)) {
            foreach ($userData as $guild) {
                echo "<button type='button' class='btn btn-dark m-1'>{$guild['name']}</button>";
            }
        } else {
            echo "The user is not a member of any guilds.";
        }

    } else {
        echo "Failed to fetch user data from Discord.";
    }
  } else {
    echo "Failed to obtain access token from Discord.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>    
    <title>R6 Tournament System</title>

    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="content/icon.png"/>
    <link rel="icon" href="content/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#ff0000"/>
    <meta name="description" content="Morgan.Games R6 Tournament System"/>
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="hover.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/66afee0429.js" crossorigin="anonymous"></script>
    
  </head>

  <body class="bg">
      <div class="alert alert-warning">
        <strong>Important!</strong> This tournament management system is in active development if you wish to use it for your tournament you should first speak to <a href="https://morgan.games/">@MorganSkilly</a>.
      </div>
      
      <?php include('navigation.php'); ?>

      <div class="container">
        <div class="mt-4"> 
          <?php returnGuilds();?>

        </div>        
      </div>

      <?php include('footer.php'); ?>
  </body>
</html>