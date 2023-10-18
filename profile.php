<?php
session_start();

if(!$_SESSION['logged_in']){
  header('Location: error.php?error=notloggedin');
  exit();
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
  
      
      <?php include('navigation.php'); ?>

      <div class="container">
        <div class="mt-5 w-50 mx-auto"> 
          <?php
            if (isset($_GET['user']))
            {
              $user_id = $_GET['user'];
              $token = $_SESSION['bot_token'];
              $url = "https://discord.com/api/v10/users/$user_id";
              $headers = [
                "Authorization: Bot $token",
              ];

              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $response = curl_exec($ch);

              if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200)
              {
                $user_data = json_decode($response, true);
                $avatar_hash = $user_data['avatar'];

                $size = 256; // You can change this to your desired size (e.g., 128, 256)
                $default = 'default'; // You can set this to 'default' for the default avatar
                
                $avatar_url = "https://cdn.discordapp.com/avatars/$user_id/$avatar_hash.png?size=$size&default=$default";
                
                $host = $_SESSION['sql_host'];
                $username = $_SESSION['sql_username'];
                $password = $_SESSION['sql_password'];
                $database = $_SESSION['sql_database'];
                
                $conn = new mysqli($host, $username, $password, $database);

                if ($conn->connect_error) {
                  die('Connection failed: ' . $conn->connect_error);
                }

                $sql = "SELECT ubisoft_name, twitter_name, twitch_name FROM users WHERE discord_id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();

                $stmt->bind_result($ubisoft_name, $twitter_name, $twitch_name);

                if ($stmt->fetch())
                {
                  $ubisoft_name = "https://r6.tracker.network/profile/pc/" . $ubisoft_name;
                  $twitter_name = "https://twitter.com/" . $twitter_name;
                  $twitch_name = "https://www.twitch.tv/" . $twitch_name;


                  

                  if (isset($_GET['edit'])){
                    echo "<img class='img-fluid rounded-circle' src='$avatar_url' width='50vw' height='50vw'>
                    <hr><h2>" . $user_data['username'] . "</h2>";

                    echo "
                    <form method='POST' action='update-profile.php'>
                      <div class='form-group'>
                        <label for='twitch'>Twitch</label>
                        <input type='text' class='form-control form-control-sm' id='twitch' name='twitch' placeholder='Enter Twitch name here (leave blank to remain unchanged)'>
                      
                        <label for='twitter'>Twitter</label>
                        <input type='text' class='form-control form-control-sm' id='twitter' name='twitter' placeholder='Enter Twitter name here (leave blank to remain unchanged)'>
                                              
                        <label for='ubisoft'>Ubisoft</label>
                        <input type='text' class='form-control form-control-sm' id='ubisoft' name='ubisoft' placeholder='Enter Ubisoft name here (leave blank to remain unchanged)'>
                      </div>
                      <button type='submit' class='btn btn-outline-info'>Update</button>
                    </form>
                    ";
                  }
                  else
                  {
                    if ($user_id === $_SESSION['discord_user_data']['id'])
                    {
                      echo "<br><a href='?user=" . $user_id . "&edit=TRUE'>edit profile <i class='fa-solid fa-pen-to-square'></i></a><hr>";
                    }

                    echo "<img class='img-fluid rounded-circle' src='$avatar_url' width='150vw' height='150vw'>";

                    echo "<h2>" . $user_data['username'] . "</h2><br>";

                    if ($ubisoft_name != "")
                    {
                      require 'simple_html_dom.php';

                      if($html = @file_get_contents($ubisoft_name))
                      {
                        $dom = new simple_html_dom();
                        $dom->load($html);
  
                        $rank1 = $dom->find('div.r6-quickseason__image', 3);
                        $rank2 = $dom->find('div.r6-quickseason__image', 2);
                        $rank3 = $dom->find('div.r6-quickseason__image', 1);
                        $seasonkd = $dom->find('div.trn-defstat__value', 8);
                        $lifekd = $dom->find('div.trn-defstat__value', 10);
                        $matches = $dom->find('div.trn-card__header-subline', 0);
  
                        if ($rank1) {
                            echo $rank1 . $rank2 . $rank3 . "<br>" . $seasonkd . " seasonal kd<br>" . $lifekd . " lifetime kd " . $matches;
                        }
                        
                        
                        $dom->clear();
                        unset($dom);
                      }
                      else
                      {
                        echo "This Ubisoft account could not be found on R6 Tracker!";
                      }

                      

                      echo "<hr>";

                    }

                    if ($twitter_name != "")
                    {
                      echo "<hr><a href='$twitter_name' target='_blank'><i class='fa-brands fa-twitter mr-3'></i></a>";
                    }
                    if ($twitch_name != "")
                    {
                      echo "<a href='$twitch_name' target='_blank'><i class='fa-brands fa-twitch mr-3'></i></a>";
                    }
                    if ($ubisoft_name != "")
                    {
                      echo "<a href='$ubisoft_name' target='_blank'><i class='fa-solid fa-chart-simple'></i></a>";
                    }
                  }
                  
                }
                else
                {
                  echo "<img class='img-fluid rounded-circle' src='$avatar_url' width='150vw' height='150vw'>
                  <hr><h2>" . $user_data['username'] . " is not active on this platform.</h2><hr>";
                }
                
              } else {
                echo "<h1>Failed to fetch user data.</h1>";
              }       

              curl_close($ch);        
            }else {
              echo "<h1>Failed to fetch user data.</h1>";
            }

            
          ?>
        </div>        
      </div>

      <?php include('footer.php'); ?>
  </body>
</html>