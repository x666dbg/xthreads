<?php

// Quick test untuk API
$url = 'http://localhost:8000/api/auth/register';

$data = [
    'username' => 'testapi_' . time(),
    'email' => 'testapi_' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

if ($httpCode === 201) {
    $responseData = json_decode($response, true);
    if (isset($responseData['data']['token'])) {
        echo "\n✅ API Registration berhasil!\n";
        echo "Token: " . $responseData['data']['token'] . "\n";
        
        // Test get timeline
        echo "\n--- Testing Timeline ---\n";
        $timelineUrl = 'http://localhost:8000/api/threads';
        $token = $responseData['data']['token'];
        
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $timelineUrl);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        
        $timelineResponse = curl_exec($ch2);
        $timelineCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);
        
        echo "Timeline HTTP Code: $timelineCode\n";
        echo "Timeline Response: $timelineResponse\n";
        
        if ($timelineCode === 200) {
            echo "\n✅ Timeline API berhasil!\n";
        }
    }
} else {
    echo "\n❌ API Registration gagal!\n";
}
