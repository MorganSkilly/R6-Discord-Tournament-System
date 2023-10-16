<?php

session_start();
if (isset($_SESSION['discord_user_data'])) {
  // User data obtained successfully

  echo '<pre>';
  var_dump($_SESSION);
  echo '</pre>';
  
  $_SESSION['logged_in'] = true;
  header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>    
    <title>Morgan.Games</title>

    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="icon.png"/>
    <link rel="icon" href="icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#ff0000"/>
    <meta name="description" content="Morgan.Games R6 Tournament System"/>
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="hover.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/66afee0429.js" crossorigin="anonymous"></script>
    
  </head>

  <body class="bg">
    <?php include('navigation.php'); ?>
    <div class="container">
        
      <div class="mt-4">
        <h1>R6 Tournament System</h1>
        <br>
        <a href="oauth.php" class="btn btn-primary btn-block hvr-grow mt-2" role="button" target="_self"><i class="fa-brands fa-discord"></i>&nbsp;Log in with Discord</a>
        <br>
      </div>

      <?php include('footer.php'); ?>
    </div>
  </body>
</html>

