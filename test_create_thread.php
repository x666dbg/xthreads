<?php

// Test create thread
$token = '2|bwnOR06imzchjkXcymsPmrjHIzcCbkDqBu6WpVpT3062e648'; // Token dari test sebelumnya

$url = 'http://localhost:8000/api/threads';

$data = [
    'content' => 'Hello from API! This is my first thread via API at ' . date('Y-m-d H:i:s')
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Create Thread HTTP Code: $httpCode\n";
echo "Create Thread Response: $response\n";

if ($httpCode === 201) {
    echo "\n✅ Thread berhasil dibuat!\n";
    
    $responseData = json_decode($response, true);
    $threadId = $responseData['data']['thread']['id'];
    
    // Test like thread
    echo "\n--- Testing Like Thread ---\n";
    $likeUrl = "http://localhost:8000/api/threads/$threadId/toggle-like";
    
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $likeUrl);
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    
    $likeResponse = curl_exec($ch2);
    $likeCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);
    
    echo "Like HTTP Code: $likeCode\n";
    echo "Like Response: $likeResponse\n";
    
    if ($likeCode === 200) {
        echo "\n✅ Like berhasil!\n";
    }
    
    // Test get updated timeline
    echo "\n--- Testing Updated Timeline ---\n";
    $timelineUrl = 'http://localhost:8000/api/threads';
    
    $ch3 = curl_init();
    curl_setopt($ch3, CURLOPT_URL, $timelineUrl);
    curl_setopt($ch3, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
    
    $timelineResponse = curl_exec($ch3);
    $timelineCode = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
    curl_close($ch3);
    
    echo "Updated Timeline HTTP Code: $timelineCode\n";
    echo "Updated Timeline Response: $timelineResponse\n";
    
} else {
    echo "\n❌ Thread gagal dibuat!\n";
}
