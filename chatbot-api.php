<?php
/* ============================================================================
 *                              chatbot-api.php
 *
 *   Server-side proxy for the "KL Travel Concierge" chatbot bubble
 *   (assets/js/chatbot.js posts here; the reply is rendered in the widget).
 *
 *   Flow: POST {message, history[]} -> Google Gemini (gemini-2.5-flash)
 *         -> JSON {reply} back to the browser.
 *
 *   It exists so the GEMINI_API_KEY never reaches the browser. The key is read
 *   straight out of the root .env with a small hand-rolled parser — this file
 *   deliberately does NOT include admin/functions.php, to skip the DB + session
 *   overhead on every chat message. If the key is missing, every call 500s.
 *
 *   What you'd change here: the $systemInstruction block below is the bot's
 *   whole personality and its list of linkable site pages — edit that to change
 *   how it answers or which pages it recommends. Chat history is capped to the
 *   last 20 turns to stay inside the token budget.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

// Parse .env from root (avoid loading full functions.php to skip DB/session overhead)
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
    }
}

$apiKey = $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY') ?? '';
if (empty($apiKey)) {
    http_response_code(500);
    exit(json_encode(['error' => 'Gemini API key not configured. Add GEMINI_API_KEY to your .env file.']));
}

$body    = json_decode(file_get_contents('php://input'), true);
$message = trim($body['message'] ?? '');
$history = is_array($body['history'] ?? null) ? $body['history'] : [];

if ($message === '') {
    http_response_code(400);
    exit(json_encode(['error' => 'Empty message']));
}

// Cap history to last 10 exchanges to stay within token limits
$history = array_slice($history, -20);

$systemInstruction = "You are KL Travel Concierge, the friendly and knowledgeable AI assistant for KL The Guide (kltheguide.com.my) — Kuala Lumpur's premier travel discovery website.

Your role is to help visitors:
- Plan personalised day-by-day itineraries for Kuala Lumpur
- Discover the best places to eat (hawker food, fine dining, halal, vegetarian)
- Find accommodation for every budget (backpacker hostels to 5-star hotels)
- Explore top KL attractions, landmarks, and hidden gems
- Discover shopping malls, markets, and local boutiques
- Find spas, wellness centres, and relaxation spots
- Learn about medical tourism facilities in KL
- Discover day trips and destinations Beyond KL

Tone: warm, enthusiastic, conversational. Use short paragraphs. Use bullet points for lists. Use emojis sparingly (1–2 per reply max).

When relevant, direct users to these KL The Guide pages using ONLY the markdown link format shown — never show raw file paths:
- [Explore KL](https://kltheguide.com.my/explorekl.php)
- [Blog & Articles](https://kltheguide.com.my/blog.php)
- [Accommodation](https://kltheguide.com.my/accommodation.php)
- [Places to Shop](https://kltheguide.com.my/places-to-shop.php)
- [Spa & Wellness](https://kltheguide.com.my/spa.php)
- [Medical Tourism](https://kltheguide.com.my/medical-tourism.php)
- [Beyond KL](https://kltheguide.com.my/beyondkl.php)
- [Events in KL](https://kltheguide.com.my/event.php)

Always format page links as [Friendly Name](full URL). Never write out raw .php filenames in your reply.

For itineraries: structure as Morning / Afternoon / Evening per day.
If asked about something unrelated to KL or travel, politely redirect back to your role.
Keep replies under 300 words unless the user explicitly asks for a detailed itinerary.";

$contents = [];
foreach ($history as $msg) {
    $role = $msg['role'] === 'model' ? 'model' : 'user';
    $contents[] = ['role' => $role, 'parts' => [['text' => (string)$msg['text']]]];
}
$contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

$payload = [
    'system_instruction' => ['parts' => [['text' => $systemInstruction]]],
    'contents'           => $contents,
    'generationConfig'   => [
        'temperature'     => 0.7,
        'maxOutputTokens' => 1024,
    ],
    'safetySettings' => [
        ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
    ],
];

$ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . urlencode($apiKey));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
unset($ch);

if ($response === false) {
    http_response_code(500);
    exit(json_encode(['error' => 'cURL error: ' . $curlErr]));
}

$data = json_decode($response, true);

if ($httpCode !== 200) {
    http_response_code(500);
    exit(json_encode(['error' => $data['error']['message'] ?? 'Gemini API error (HTTP ' . $httpCode . ')']));
}

$reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
if ($reply === null) {
    http_response_code(500);
    exit(json_encode(['error' => 'No response from Gemini']));
}

echo json_encode(['reply' => $reply]);
