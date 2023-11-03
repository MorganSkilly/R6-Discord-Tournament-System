<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the raw request data
    $rawData = file_get_contents('php://input');
    
    // Parse the JSON data
    $data = json_decode($rawData, true);

    // Check if it's a valid command
    if (isset($data['type']) && $data['type'] === 1) {
        // Handle the command
        $commandName = $data['data']['name'];

        // Customize your response based on the command
        if ($commandName === 'test') {
            $response = "You invoked the /test command!";
        } else {
            $response = "Unknown command: $commandName";
        }

        // Build the response array
        $replyData = [
            'type' => 4,
            'data' => [
                'content' => $response,
            ],
        ];

        // Encode the response as JSON
        $encodedResponse = json_encode($replyData);

        // Send the response back to Discord
        header('Content-Type: application/json');
        echo $encodedResponse;
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}