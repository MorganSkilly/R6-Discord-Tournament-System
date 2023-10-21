<?php

require "simple_html_dom.php";

function get_player_rating($stats_array)
{
    echo var_dump($stats_array);

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

    echo "<h1>" . $_GET['user'] . " player rating: " . $rating . "</h1>";
    return $rating;
}

function get_player_stats($stats_url)
{
    echo("<br> <a href='" . $stats_url . "' target='_blank'>" . $stats_url . "</a><br>");

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
            
            $playerStats['last_season_mmr'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 30)->plaintext));
            $playerStats['last_season_max_mmr'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 31)->plaintext));
            $playerStats['last_season_kd'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 20)->plaintext));
            $playerStats['last_season_kills_per_match'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 21)->plaintext));
            $playerStats['last_season_kills'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 22)->plaintext));
            $playerStats['last_season_deaths'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 23)->plaintext));
            $playerStats['last_season_win_percentage'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 24)->plaintext));
            $playerStats['last_season_wins'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 25)->plaintext));
            $playerStats['last_season_losses'] = floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 26)->plaintext));
            $playerStats['last_season_matches_played'] = $playerStats['last_season_wins'] + $playerStats['last_season_losses'];
            
            $playerStats['average_season_mmr'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 30)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 78)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 126)->plaintext))) / 3;

            $playerStats['average_season_max_mmr'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 31)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 79)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 127)->plaintext))) / 3;

            $playerStats['average_season_kd'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 20)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 68)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 116)->plaintext))) / 3;

            $playerStats['average_season_kills_per_match'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 21)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 69)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 117)->plaintext))) / 3;

            $playerStats['average_season_kills'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 22)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 70)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 118)->plaintext))) / 3;

            $playerStats['average_season_deaths'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 23)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 71)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 119)->plaintext))) / 3;

            $playerStats['average_season_win_percentage'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 24)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 72)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 120)->plaintext))) / 3;

            $playerStats['average_season_wins'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 25)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 73)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 121)->plaintext))) / 3;

            $playerStats['average_season_losses'] = (
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 26)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 74)->plaintext)) + 
                floatval(preg_replace('/[^\d.]/', '', $dom->find('.trn-defstat__value', 122)->plaintext))) / 3;

            $playerStats['average_season_matches_played'] = $playerStats['average_season_wins'] + $playerStats['average_season_losses'];

        }

        $dom->clear();
        unset($dom);
    }
    else
    {
        echo "This Ubisoft account could not be found on R6 Tracker!";
    }

    return $playerStats;
}

if (isset($_GET['user']))
{   
    $playerStats = get_player_stats("https://r6.tracker.network/profile/pc/" . $_GET['user']);

    get_player_rating($playerStats);

    foreach ($playerStats as $key => $value) {
        echo "$key: $value<br>";
    }
}
else
{
    echo "user not set";
}
