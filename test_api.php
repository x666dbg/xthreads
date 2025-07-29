<?php

/**
 * Simple API Test Script for XThreads
 * 
 * Run this script to test the API endpoints:
 * php test_api.php
 */

$baseUrl = 'http://localhost/api';
$token = null;

function makeRequest($method, $url, $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data) {
        if ($method === 'GET') {
            $url .= '?' . http_build_query($data);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

function testEndpoint($name, $method, $endpoint, $data = null, $useAuth = false) {
    global $baseUrl, $token;
    
    echo "\n=== Testing: $name ===\n";
    echo "Request: $method $endpoint\n";
    
    $headers = [];
    if ($useAuth && $token) {
        $headers[] = "Authorization: Bearer $token";
    }
    
    $response = makeRequest($method, $baseUrl . $endpoint, $data, $headers);
    
    echo "Status: " . $response['status'] . "\n";
    echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n";
    
    return $response;
}

// Test 1: Register
echo "Starting API Tests...\n";

$registerData = [
    'username' => 'testuser_' . time(),
    'email' => 'test_' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

$response = testEndpoint('Register User', 'POST', '/auth/register', $registerData);

if ($response['status'] === 201 && isset($response['body']['data']['token'])) {
    $token = $response['body']['data']['token'];
    echo "✅ Registration successful! Token obtained.\n";
} else {
    echo "❌ Registration failed!\n";
    exit(1);
}

// Test 2: Get current user
testEndpoint('Get Current User', 'GET', '/auth/me', null, true);

// Test 3: Create a thread
$threadData = [
    'content' => 'Hello from API test! ' . date('Y-m-d H:i:s')
];

$threadResponse = testEndpoint('Create Thread', 'POST', '/threads', $threadData, true);
$threadId = null;

if ($threadResponse['status'] === 201 && isset($threadResponse['body']['data']['thread']['id'])) {
    $threadId = $threadResponse['body']['data']['thread']['id'];
    echo "✅ Thread created with ID: $threadId\n";
} else {
    echo "❌ Thread creation failed!\n";
}

// Test 4: Get timeline
testEndpoint('Get Timeline', 'GET', '/threads', null, true);

// Test 5: Like the thread (if created)
if ($threadId) {
    testEndpoint('Like Thread', 'POST', "/threads/$threadId/toggle-like", null, true);
}

// Test 6: Get thread details (if created)
if ($threadId) {
    testEndpoint('Get Thread Details', 'GET', "/threads/$threadId", null, true);
}

// Test 7: Search users
testEndpoint('Search Users', 'GET', '/users/search', ['q' => 'test'], true);

// Test 8: Create a reply (if thread exists)
if ($threadId) {
    $replyData = [
        'content' => 'This is a reply to the test thread!',
        'parent_thread_id' => $threadId
    ];
    
    testEndpoint('Create Reply', 'POST', '/threads', $replyData, true);
}

// Test 9: Logout
testEndpoint('Logout', 'POST', '/auth/logout', null, true);

echo "\n🎉 API Tests completed!\n";
echo "\nTo test manually, you can use tools like:\n";
echo "- Postman\n";
echo "- Insomnia\n";
echo "- curl commands\n";
echo "\nExample curl command:\n";
echo "curl -X POST $baseUrl/auth/login \\\n";
echo "  -H 'Content-Type: application/json' \\\n";
echo "  -d '{\"email\":\"test@example.com\",\"password\":\"password123\"}'\n";
