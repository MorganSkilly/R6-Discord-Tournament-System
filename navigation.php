<nav class="navbar navbar-expand-lg navbar-light">      
    <ul class="navbar-nav mr-auto">

    <?php
    if(isset($_SESSION['logged_in']))
        if($_SESSION['logged_in'])
        {
            echo "            
            <li>
            <a class='btn btn-info hvr-sink my-2 my-sm-0 mr-2' href='#'>My Tournaments</a>          
            </li>

            <li>
                <a class='btn btn-info hvr-sink my-2 my-sm-0 mr-2' href='#'>My Teams</a>
            </li>

            <li>
                <a class='btn btn-outline-info hvr-sink my-2 my-sm-0 mr-2' href='#'>Tools</a>          
            </li>

            <li>
                <a class='btn btn-outline-warning hvr-sink my-2 my-sm-0 mr-2' href='staff-tools.php'>Staff Tools</a>          
            </li>
            ";
        }
    ?>    

    </ul>

    <?php
    if(isset($_SESSION['logged_in']))
    {
        if($_SESSION['logged_in'])
        {
            $userId = $_SESSION['discord_user_data']['id']; // Replace with the user's actual ID
            $avatarHash = $_SESSION['discord_user_data']['avatar']; // Replace with the user's actual avatar hash
            $size = 256; // You can change this to your desired size (e.g., 128, 256)
            $default = 'default'; // You can set this to 'default' for the default avatar
            
            $avatar_url = "https://cdn.discordapp.com/avatars/$userId/$avatarHash.png?size=$size&default=$default";

            echo "
            <a style='color:white' class=' nav-link' href='profile.php?user=" . $userId ."'>Logged in as " . $_SESSION['discord_user_data']['username'] . "</a>
            <a href='profile.php?user=" . $userId ."'><img class='img-fluid rounded-circle mr-4' src='" . $avatar_url . "' width='30' height='30' alt=''></a>
            <a href='logout.php' class='btn btn-outline-danger hvr-sink my-2 my-sm-0' role='button' target='_self'><i class='fa-brands fa-discord'></i>&nbsp;Log Out</a>
            ";
        }
    }
    else
    {
        echo "<a class='nav-link disabled'>Not Logged In</a>
        <a href='oauth.php' class='btn btn-primary hvr-sink my-2 my-sm-0' role='button' target='_self'><i class='fa-brands fa-discord'></i>&nbsp;Log in with Discord</a>";
    }
    ?>    
</nav>