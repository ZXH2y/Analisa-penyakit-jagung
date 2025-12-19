<?php
header('Content-Type: application/json');

// Ambil API key dari environment variable (JANGAN hardcode)
$api_key = getenv('OPENAI_API_KEY') ?: null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (empty($api_key)) {
    http_response_code(500);
    echo json_encode(['error' => 'API key not configured. Set OPENAI_API_KEY in environment.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$user_message = trim($input['message'] ?? '');

if ($user_message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing "message" in request body.']);
    exit;
}

$url = 'https://api.openai.com/v1/chat/completions';
$post_data = json_encode([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => $user_message]
    ],
    'max_tokens' => 500,
    'temperature' => 0.7,
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key,
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$curl_err = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'cURL error', 'details' => $curl_err]);
    exit;
}

$decoded = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(502);
    echo json_encode(['error' => 'Invalid JSON from API', 'raw' => $response]);
    exit;
}

if ($http_code >= 400) {
    $err = $decoded['error'] ?? $decoded;
    http_response_code($http_code);
    echo json_encode(['error' => 'OpenAI API error', 'details' => $err]);
    exit;
}

$reply = $decoded['choices'][0]['message']['content'] ?? null;
if ($reply === null) {
    http_response_code(502);
    echo json_encode(['error' => 'No reply in API response', 'response' => $decoded]);
    exit;
}

// kembalikan reply bersih (trim)
echo json_encode(['reply' => trim($reply)]);