<?php
session_start();

if(!$_SESSION['logged_in']){
  header('Location: error.php?error=notloggedin');
  exit();
}

$host = $_SESSION['sql_host'];
$username = $_SESSION['sql_username'];
$password = $_SESSION['sql_password'];
$database = $_SESSION['sql_database'];

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "SELECT staff FROM users WHERE discord_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['discord_user_data']['id']);
$stmt->execute();

$stmt->bind_result($is_staff);
$stmt->fetch();

$conn->close();

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
            <?php if (!$is_staff){echo "<h1>ACCESS DENIED</h1>";}?>

            <?php
            if ($is_staff){
                // Database connection details
                $host = $_SESSION['sql_host'];
                $username = $_SESSION['sql_username'];
                $password = $_SESSION['sql_password'];
                $database = $_SESSION['sql_database'];
                $table = 'users';

                // Create a database connection
                $mysqli = new mysqli($host, $username, $password, $database);

                // Check for a successful connection
                if ($mysqli->connect_error) {
                    die('Connection failed: ' . $mysqli->connect_error);
                }

                $columns = array('id', 'name', 'ubisoft_name', 'r6_player_rating', 'discord_id', 'account_created', 'last_login'); // Replace with the actual column names you want

                // Construct the SQL query to select specific columns from the table
                $sql = 'SELECT ' . implode(', ', $columns) . ' FROM ' . $table;


                // Execute the query
                $result = $mysqli->query($sql);

                if ($result) {
                    echo '<table class="table table-striped table-dark">';
                    echo '<tr>';
                    
                    // Output table headers
                    while ($fieldinfo = $result->fetch_field()) {
                        echo '<th>' . $fieldinfo->name . '</th>';
                    }
                    
                    echo '</tr>';

                    // Output table data
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        foreach ($row as $value) {
                            echo '<td>' . $value . '</td>';
                        }
                        echo '</tr>';
                    }

                    echo '</table>';

                    // Free the result set
                    $result->free();
                } else {
                    echo 'Error: ' . $mysqli->error;
                }

                // Close the database connection
                $mysqli->close();}
            ?>

        </div>        
      </div>

      <?php include('footer.php'); ?>
  </body>
</html>