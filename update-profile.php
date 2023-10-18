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
    echo $_POST['twitch'];
    echo $_POST['twitter'];
    echo $_POST['ubisoft'];

    if(isset($_POST['twitch']) && !empty($_POST['twitch']))
    {
        $sql1 = "UPDATE users SET twitch_name = ? WHERE discord_id = ?";

        $stmtupdate1 = $conn->prepare($sql1);
        $stmtupdate1->bind_param("si", $_POST['twitch'], $discord_id);

        if ($stmtupdate1->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating twitch name: " . $stmtupdate1->error;
        }
    }
    if(isset($_POST['twitter']) && !empty($_POST['twitter']))
    {
        $sql2 = "UPDATE users SET twitter_name = ? WHERE discord_id = ?";

        $stmtupdate2 = $conn->prepare($sql2);
        $stmtupdate2->bind_param("si", $_POST['twitter'], $discord_id);

        if ($stmtupdate2->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating twitter name: " . $stmtupdate2->error;
        }
    }
    if(isset($_POST['ubisoft']) && !empty($_POST['ubisoft']))
    {
        $sql3 = "UPDATE users SET ubisoft_name = ? WHERE discord_id = ?";

        $stmtupdate3 = $conn->prepare($sql3);
        $stmtupdate3->bind_param("si", $_POST['ubisoft'], $discord_id);

        if ($stmtupdate3->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating ubisoft name: " . $stmtupdate3->error;
        }
    }

    header("Location: profile.php?user=" . $discord_id);
    

}
?>