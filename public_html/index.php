<?php

session_start();

if(isset($_SESSION['logged_in'])){
  header('Location: dashboard.php');
  exit();
}

if (isset($_SESSION['discord_user_data'])) {

  $_SESSION['logged_in'] = true;
  header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>    
    <title>Morgan.Games</title>

    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="content/icon.png"/>
    <link rel="icon" href="content/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#ff0000"/>
    <meta name="description" content="Morgan.Games R6 Tournament System"/>
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="hover.css">
    <link rel="stylesheet" href="stylelanding.css">
    <script src="https://kit.fontawesome.com/66afee0429.js" crossorigin="anonymous"></script>
    
  </head>

  <body class="bg">    

    <?php include('navigation.php'); ?>


    <div class="container">
        
      <div class="mt-4">
        <br>
        <h1>Morgan.Games Tournament System</h1>
        <h2>built by @MorganSkilly</h2>
        <br>
        <p>This system is in active development. I am looking for tournaments to work with as I develop this system. I intend to fully integrate it with Discord as well as building an API for live production integration. If you are interested contact me on Twitter or Discord or email hello@morgan.games.</p>
        <br>
        <br>
      </div>

      <a href='oauth.php' class='btn btn-primary hvr-sink my-2 my-sm-0' role='button' target='_self'><i class='fa-brands fa-discord'></i>&nbsp;Log in with Discord</a>

      <?php include('footer.php'); ?>
    </div>
  </body>
</html>

