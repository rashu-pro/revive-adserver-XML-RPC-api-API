<?php
// Revive Adserver XML-RPC endpoint
$serverUrl = "https://adserver.masjidapps.net/revive-adserver/www/api/v2/xmlrpc/";

// Replace with your Revive Adserver admin credentials
$username = 'revive_adserver_admin';
$password = 'test5454webPassword';

// Function to make XML-RPC calls using cURL
function xmlRpcRequest($method, $params)
{
    global $serverUrl;

    // Prepare XML-RPC payload
    $request = xmlrpc_encode_request($method, $params);

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serverUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml',
        'Content-Length: ' . strlen($request)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

    // Execute the request and decode the response
    $response = curl_exec($ch);
    curl_close($ch);

    return xmlrpc_decode($response);
}

// Step 1: Log in to get a session ID
$sessionId = xmlRpcRequest('ox.logon', [$username, $password]);

if ($sessionId) {
    echo "Logged in successfully. Session ID: " . $sessionId . PHP_EOL;

    // Step 2: Create a new advertiser
    $advertiserData = [
        'advertiserName' => 'Test Advertiser - using api NEW 2',
        'contactName' => 'John Doe (test)',
        'emailAddress' => 'jhonn@mail.com',
    ];

    $newAdvertiserId = xmlRpcRequest('ox.addAdvertiser', [$sessionId, $advertiserData]);

    if (is_int($newAdvertiserId)) {
        echo "New advertiser created with ID: " . $newAdvertiserId . PHP_EOL;
    } else {
        echo "Error creating advertiser: " . print_r($newAdvertiserId, true) . PHP_EOL;
    }

    // Step 3: Log out
    xmlRpcRequest('ox.logoff', [$sessionId]);
    echo "Logged out." . PHP_EOL;
} else {
    echo "Error logging in. Check your credentials." . PHP_EOL;
}


