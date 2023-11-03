<?php

session_start();

$host = $_SESSION['sql_host'];
$username = $_SESSION['sql_username'];
$password = $_SESSION['sql_password'];
$database = $_SESSION['sql_database'];

$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

$discord_id = $_SESSION['discord_user_data']['id'];

$query = "SELECT * FROM users WHERE discord_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $discord_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user_exists = TRUE;
} else {
  $user_exists = FALSE;
}

if ($user_exists)
{
  date_default_timezone_set('Europe/London');
  $current_datetime = date('Y-m-d H:i:s');

  $sql = "UPDATE users SET last_login = ? WHERE discord_id = ?";
  $stmtupdate = $conn->prepare($sql);
  $stmtupdate->bind_param("si", $current_datetime, $discord_id);

  if ($stmtupdate->execute()) {
      echo "Last login time updated successfully.";
  } else {
      echo "Error updating last login time: " . $stmtupdate->error;
  }

  header("Location: profile.php?user=" . $discord_id);
  exit();
}

else
{
  $discord_id = $_SESSION['discord_user_data']['id'];
  $name = $_SESSION['discord_user_data']['username'];
  $discord_user_data = json_encode($_SESSION['discord_user_data']);

  $sql = "INSERT INTO users (discord_id, name, discord_user_data)
  VALUES ('$discord_id', '$name', '$discord_user_data')";

  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  header("Location: profile.php?user=" . $discord_id . "&edit=TRUE");
  exit();
}