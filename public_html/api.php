<?php
require "simple_html_dom.php";

function extractDatapoint($html, $seasonName, $datapointName) 
{
    // Load the HTML content into a Simple HTML DOM object
    $htmlDOM = str_get_html($html);

    // Find the season element with the provided season name
    foreach ($htmlDOM->find('.trn-card__header-title') as $seasonElement)
    {
        //echo "<br>" . $seasonElement;

        if (trim($seasonElement->plaintext) === $seasonName)
        {
            // The season element has been found
            // Search for the datapoint within this season element
            $season = $seasonElement->parent()->parent(); // Parent element of the season title
            //echo "<br>" . $season;

            foreach ($season->find('.trn-defstat__name') as $datapointElement)
            {
                if (trim($datapointElement->plaintext) === $datapointName)
                {
                    // The datapoint element has been found
                    // Extract and return the datapoint value
                    $datapointValue = $datapointElement->next_sibling();
                    //echo "<br>" . $datapointElement;
                    //echo floatval(preg_replace('/[^\d.]/', '', trim($datapointValue)));
                    return floatval(preg_replace('/[^\d.]/', '', trim($datapointValue)));
                }
            }
        }
        
    }

    // Season or datapoint not found
    return "Season or datapoint not found";
}

function get_player_rating($stats_array)
{
    //echo var_dump($stats_array);

    $rating = 0;

    if ($stats_array['level'] > 300)
    {
        $rating = $rating + 15;
    }
    else
    {
        $rating = $rating + (0.05 * $stats_array['level']);
    }
    
    $rating = $rating + ($stats_array['last_season_mmr'] / 100);
    $rating = $rating + (0.1 * ($stats_array['last_season_wins'] / 100));
    $rating = $rating + (0.2 * $stats_array['last_season_kills_per_match']);
    $rating = $rating + (0.2 * ($stats_array['last_season_kills'] / 10));
    $rating = $rating + (($stats_array['last_season_kd'] - 1) * 10);
    $rating = $rating + (2 * $stats_array['last_season_win_percentage']);
    
    if ($stats_array['last_season_matches_played'] > 300)
    {
        $rating = $rating + (300 * 0.05);
    }
    else
    {
        $rating = $rating + ($stats_array['last_season_matches_played'] * 0.05);
    }
    
    $rating = $rating / 3;

    if($rating < 0)
    {
        $rating = 0;
    }

    return $rating;
}

function get_player_stats($stats_url)
{
    $playerStats = array(
        'overall_best_mmr' => 0,
        'level' => 0,
        'overall_wins' => 0,
        'overall_win_percentage' => 0,
        'overall_kills' => 0,
        'overall_ranked_kd' => 0,
        'last_season_mmr' => 0,
        'last_season_max_mmr' => 0,
        'last_season_kd' => 0,
        'last_season_kills_per_match' => 0,
        'last_season_kills' => 0,
        'last_season_deaths' => 0,
        'last_season_win_percentage' => 0,
        'last_season_wins' => 0,
        'last_season_losses' => 0,
        'last_season_matches_played' => 0,
        'average_season_mmr' => 0,
        'average_season_max_mmr' => 0,
        'average_season_kd' => 0,
        'average_season_kills_per_match' => 0,
        'average_season_kills' => 0,
        'average_season_deaths' => 0,
        'average_season_win_percentage' => 0,
        'average_season_wins' => 0,
        'average_season_losses' => 0,
        'average_season_matches_played' => 0
    );

    if($html = @file_get_contents($stats_url))
    {
        $dom = new simple_html_dom();
        $dom->load($html);

        $playerStats['overall_best_mmr'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value-stylized', 0)->plaintext));
        $playerStats['level'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value-stylized', 1)->plaintext));
        $playerStats['overall_wins'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 1)->plaintext));
        $playerStats['overall_win_percentage'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 2)->plaintext));
        $playerStats['overall_kills'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 3)->plaintext));
        $playerStats['overall_ranked_kd'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 4)->plaintext));

        if($html = @file_get_contents($stats_url . "/seasons"))
        {
            $dom->clear();
            $dom->load($html);
            
            $playerStats['last_season_mmr'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Rank Points");
            $playerStats['last_season_max_mmr'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Max Rank Points");

            $playerStats['last_season_kd'] = extractDatapoint($dom, "Y8S2: Dread Factor", "K/D");

            $playerStats['last_season_kills_per_match'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Kills/Match");
            $playerStats['last_season_kills'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Kills");
            $playerStats['last_season_deaths'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Deaths");
            $playerStats['last_season_win_percentage'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Win %");
            $playerStats['last_season_wins'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Wins");
            $playerStats['last_season_losses'] = extractDatapoint($dom, "Y8S2: Dread Factor", "Losses");
            $playerStats['last_season_matches_played'] = $playerStats['last_season_wins'] + $playerStats['last_season_losses'];
            
            $playerStats['average_season_mmr'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Rank Points") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Rank Points") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Rank Points"))/ 3;

            $playerStats['average_season_max_mmr'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Max Rank Points") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Max Rank Points") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Max Rank Points")) / 3;

            $playerStats['average_season_kd'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "K/D") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "K/D") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "K/D")) / 3;

            $playerStats['average_season_kills_per_match'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Kills/Match") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Kills/Match") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Kills/Match")) / 3;

            $playerStats['average_season_kills'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Kills") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Kills") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Kills")) / 3;

            $playerStats['average_season_deaths'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Deaths") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Deaths") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Deaths")) / 3;

            $playerStats['average_season_win_percentage'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Win %") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Win %") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Win %")) / 3;

            $playerStats['average_season_wins'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Wins") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Wins") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Wins")) / 3;

            $playerStats['average_season_losses'] = (
                extractDatapoint($dom, "Y8S2: Dread Factor", "Losses") + 
                extractDatapoint($dom, "Y8S1: Commanding Force", "Losses") + 
                extractDatapoint($dom, "Y7S4: Solar Raid", "Losses")) / 3;

            $playerStats['average_season_matches_played'] = $playerStats['average_season_wins'] + $playerStats['average_season_losses'];

        }

        $dom->clear();
        unset($dom);
    }
    else
    {
        echo "This Ubisoft account could not be found!";
    }

    return $playerStats;
}

if (!isset($_SESSION['internal_api_call']))
{
    if (isset($_GET['apikey']))
    {   
        if (isset($_GET['user']))
        {   
            if (isset($_GET['request']))
            {   
                if ($_GET['request'] === "rating")
                {   
                    $playerRating = get_player_rating(get_player_stats($_SESSION['r6_stats_endpoint'] . $_GET['user']));

                    if ($playerRating != 0)
                    {
                        header("Content-Type: application/json");
                        echo json_encode($playerRating);
                    }
                }
                else if($_GET['request'] === "stats")
                {
                    $playerStats = get_player_stats($_SESSION['r6_stats_endpoint'] . $_GET['user']);

                    header("Content-Type: application/json");
                    echo json_encode($playerStats);

                    /*foreach ($playerStats as $key => $value)
                    {
                        echo "$key: $value<br>";
                    }*/
                }
                else
                {
                    echo "invalid request parameter provided";
                }         

                
            }
            else
            {
                echo "request parameter must be set to make api calls";
            }    
        }
        else
        {
            echo "user parameter must be set to make api calls";
        }
    }
    else
    {
        echo "apikey parameter must be set to make api calls";
    }
}

