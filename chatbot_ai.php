<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize conversation context in session
if (!isset($_SESSION['conversation_context'])) {
    $_SESSION['conversation_context'] = [
        'recent_queries' => [],
        'mentioned_professors' => [],
        'searched_subjects' => [],
        'last_action' => null,
        'conversation_flow' => []
    ];
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbot";

// Attempt database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection with detailed error
if ($conn->connect_error) {
    error_log("❌ Database Connection Error: " . $conn->connect_error);
    die(json_encode([
        "error" => "Database connection failed",
        "message" => "Could not connect to MySQL. Please ensure XAMPP MySQL is running.",
        "details" => $conn->connect_error
    ]));
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

function respondJson(array $payload): void {
    echo json_encode($payload);
    exit();
}

// ═══════════════════════════════════════════════════════════
// 🗄️ DATABASE-BASED RESPONSE SYSTEM
// ═══════════════════════════════════════════════════════════

/**
 * Detect intent using database patterns
 */
function detectIntentFromDB($conn, string $query): string {
    $query = strtolower($query);
    $intentScores = [];
    
    // Get all active patterns
    $stmt = $conn->prepare("SELECT intent, pattern, weight FROM intent_patterns WHERE is_active = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        if (stripos($query, $row['pattern']) !== false) {
            $intent = $row['intent'];
            $weight = (int)$row['weight'];
            
            if (!isset($intentScores[$intent])) {
                $intentScores[$intent] = 0;
            }
            $intentScores[$intent] += $weight;
        }
    }
    $stmt->close();
    
    // Return intent with highest score
    if (!empty($intentScores)) {
        arsort($intentScores);
        return array_key_first($intentScores);
    }
    
    return 'unknown';
}

/**
 * Get response from database based on intent and sentiment
 */
function getResponseFromDB($conn, string $intent, string $sentiment = 'neutral'): ?string {
    // Try to get response for specific sentiment
    $stmt = $conn->prepare("SELECT response_text FROM responses WHERE intent = ? AND sentiment = ? AND priority >= 1 ORDER BY priority DESC, RAND() LIMIT 1");
    $stmt->bind_param('ss', $intent, $sentiment);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        return $row['response_text'];
    }
    $stmt->close();
    
    // Fallback to neutral sentiment
    if ($sentiment !== 'neutral') {
        $stmt = $conn->prepare("SELECT response_text FROM responses WHERE intent = ? AND sentiment = 'neutral' LIMIT 1");
        $stmt->bind_param('s', $intent);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row['response_text'];
        }
        $stmt->close();
    }
    
    return null;
}

/**
 * Detect sentiment from query (simple keyword-based)
 */
function detectSentimentSimple(string $query): string {
    $query = strtolower($query);
    
    $positiveWords = ['happy', 'great', 'good', 'excellent', 'awesome', 'wonderful', 'love', 'best', 'perfect', 'amazing'];
    $negativeWords = ['sad', 'bad', 'terrible', 'awful', 'hate', 'worst', 'angry', 'frustrated', 'upset', 'horrible'];
    
    $positiveCount = 0;
    $negativeCount = 0;
    
    foreach ($positiveWords as $word) {
        if (stripos($query, $word) !== false) $positiveCount++;
    }
    
    foreach ($negativeWords as $word) {
        if (stripos($query, $word) !== false) $negativeCount++;
    }
    
    if ($positiveCount > $negativeCount) return 'positive';
    if ($negativeCount > $positiveCount) return 'negative';
    return 'neutral';
}

/**
 * Save conversation to database
 */
function saveConversationToDB($conn, string $userMessage, string $botResponse, array $metadata = []): bool {
    $userId = $_SESSION['user_id'] ?? 'guest_' . session_id();
    $sessionId = session_id();
    $intent = $metadata['intent'] ?? null;
    $sentiment = $metadata['sentiment'] ?? null;
    $tone = $metadata['tone'] ?? null;
    $responseTime = $metadata['response_time_ms'] ?? null;
    
    $stmt = $conn->prepare("INSERT INTO conversation_logs (user_id, session_id, user_message, bot_response, intent, sentiment, tone, response_time_ms) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssi', $userId, $sessionId, $userMessage, $botResponse, $intent, $sentiment, $tone, $responseTime);
    
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}

// ═══════════════════════════════════════════════════════════


function normalizeText(string $text): string {
    $normalized = strtolower($text);
    $normalized = preg_replace('/[^a-z0-9\s]/', ' ', $normalized);
    $normalized = preg_replace('/\s+/', ' ', $normalized);
    return trim($normalized);
}

function containsAny(string $haystack, array $needles): bool {
    $normalizedHaystack = ' ' . normalizeText($haystack) . ' ';
    foreach ($needles as $needle) {
        $normalizedNeedle = normalizeText($needle);
        if ($normalizedNeedle === '') {
            continue;
        }

        if (str_contains($normalizedHaystack, ' ' . $normalizedNeedle . ' ')) {
            return true;
        }
    }

    return false;
}

function tokenizeNormalized(string $normalized): array {
    if ($normalized === '') {
        return [];
    }

    $rawTokens = explode(' ', $normalized);
    $deduplicated = [];

    foreach ($rawTokens as $token) {
        $token = trim($token);
        if ($token === '') {
            continue;
        }

        if (strlen($token) < 2 && !ctype_digit($token)) {
            continue;
        }

        $deduplicated[$token] = true;
    }

    return array_keys($deduplicated);
}

function computeTextSimilarity(string $a, string $b): float {
    if ($a === '' || $b === '') {
        return 0.0;
    }

    similar_text($a, $b, $percent);
    $similarity = $percent / 100;

    if ($similarity >= 0.88) {
        return min(1.0, $similarity);
    }

    $maxLength = max(strlen($a), strlen($b), 1);
    $distance = levenshtein($a, $b);
    $levenshteinScore = 1 - ($distance / $maxLength);

    return max(0.0, max($similarity, $levenshteinScore));
}

function computeMatchScore(array $queryTokens, array $candidateTokens, string $queryNormalized, string $candidateNormalized): float {
    if ($candidateNormalized === '' || $queryNormalized === '') {
        return 0.0;
    }

    if ($candidateNormalized === $queryNormalized) {
        return 1.0;
    }

    $sharedTokens = array_values(array_intersect($queryTokens, $candidateTokens));
    $sharedCount = count($sharedTokens);
    $candidateTokenCount = count($candidateTokens);
    $queryTokenCount = count($queryTokens);

    $candidateCoverage = $candidateTokenCount > 0 ? $sharedCount / $candidateTokenCount : 0.0;
    $queryCoverage = $queryTokenCount > 0 ? $sharedCount / $queryTokenCount : 0.0;

    if ($candidateTokenCount > 3 && $candidateCoverage < 0.34) {
        return 0.0;
    }

    if ($candidateTokenCount > 1 && $sharedCount === 0) {
        return 0.0;
    }

    $tokenScore = ($candidateCoverage + $queryCoverage) / 2;
    $textSimilarity = computeTextSimilarity($queryNormalized, $candidateNormalized);

    if ($tokenScore === 0.0 && $textSimilarity < 0.82) {
        return 0.0;
    }

    return ($tokenScore * 0.65) + ($textSimilarity * 0.35);
}

function findBestMatch(string $query, array $candidates, float $minimumScore = 0.55): ?array {
    $normalizedQuery = normalizeText($query);
    if ($normalizedQuery === '') {
        return null;
    }

    $queryTokens = tokenizeNormalized($normalizedQuery);
    $bestMatch = null;

    foreach ($candidates as $candidate) {
        $normalizedCandidate = trim((string) ($candidate['normalized'] ?? ''));
        if ($normalizedCandidate === '') {
            continue;
        }

        $candidateTokens = $candidate['tokens'] ?? tokenizeNormalized($normalizedCandidate);
        $score = computeMatchScore($queryTokens, $candidateTokens, $normalizedQuery, $normalizedCandidate);

        if ($score <= 0.0) {
            continue;
        }

        if ($bestMatch === null || $score > $bestMatch['score']) {
            $bestMatch = [
                'label' => $candidate['original'] ?? '',
                'score' => $score,
                'tokens' => $candidateTokens,
            ];
        }
    }

    if ($bestMatch !== null && $bestMatch['label'] !== '' && $bestMatch['score'] >= $minimumScore) {
        return $bestMatch;
    }

    return null;
}

function getSchedulesForProfessor(mysqli $conn, int $professorId): array {
    $stmt = $conn->prepare("SELECT subject, day, time, room, schedule_file FROM schedules WHERE professor_id = ?");
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param('i', $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
    return $schedules;
}

function hydrateProfessorRow(mysqli $conn, array $row): array {
    $row['schedules'] = getSchedulesForProfessor($conn, (int) $row['professor_id']);
    return $row;
}

function buildProfessorIndex(mysqli $conn): array {
    $index = [];
    $result = $conn->query("SELECT professor_name FROM professors");
    if (!$result) {
        return $index;
    }
    while ($row = $result->fetch_assoc()) {
        $normalized = normalizeText($row['professor_name']);
        $index[] = [
            'original' => $row['professor_name'],
            'normalized' => $normalized,
            'tokens' => tokenizeNormalized($normalized)
        ];
    }
    return $index;
}

function buildSubjectIndex(mysqli $conn): array {
    $index = [];
    $result = $conn->query("SELECT DISTINCT subject FROM schedules WHERE subject <> ''");
    if (!$result) {
        return $index;
    }
    while ($row = $result->fetch_assoc()) {
        $normalized = normalizeText($row['subject']);
        $index[] = [
            'original' => $row['subject'],
            'normalized' => $normalized,
            'tokens' => tokenizeNormalized($normalized)
        ];
    }
    return $index;
}

function fetchDistinctSubjects(mysqli $conn, int $limit = 6): array {
    $limit = max(1, min(20, $limit));
    $stmt = $conn->prepare("SELECT subject, COUNT(*) AS frequency FROM schedules WHERE subject <> '' GROUP BY subject ORDER BY frequency DESC, subject ASC LIMIT ?");
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subject = trim((string) ($row['subject'] ?? ''));
        if ($subject !== '') {
            $subjects[] = $subject;
        }
    }
    $stmt->close();
    return $subjects;
}

function loadProfessorByName(mysqli $conn, string $name): ?array {
    $stmt = $conn->prepare("SELECT * FROM professors WHERE professor_name = ?");
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

function composeScheduleAnswer(string $professorName, array $schedules): string {
    if (empty($schedules)) {
        return $professorName . " does not have any schedules saved yet.";
    }

    $parts = [];
    foreach ($schedules as $schedule) {
        $subject = trim((string) ($schedule['subject'] ?? ''));
        $day = trim((string) ($schedule['day'] ?? ''));
        $time = trim((string) ($schedule['time'] ?? ''));
        $room = trim((string) ($schedule['room'] ?? ''));

        $subject = $subject !== '' ? $subject : 'Class';
        $day = $day !== '' ? $day : 'unspecified day';
        $time = $time !== '' ? $time : 'unspecified time';
        $room = $room !== '' ? $room : 'unspecified room';

        $parts[] = $subject . ' on ' . $day . ' at ' . $time . ' in ' . $room;
    }

    return $professorName . ' teaches ' . implode('; ', $parts) . '.';
}

function getSchedulesBySubject(mysqli $conn, string $subject): array {
    $stmt = $conn->prepare("SELECT s.subject, s.day, s.time, s.room, s.schedule_file, p.professor_name, p.professor_id, p.photo FROM schedules s JOIN professors p ON p.professor_id = s.professor_id WHERE s.subject LIKE CONCAT('%', ?, '%') ORDER BY s.day, s.time, p.professor_name");
    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('s', $subject);
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }

    $stmt->close();
    return $schedules;
}

function composeSubjectScheduleAnswer(string $subject, array $entries): string {
    if (empty($entries)) {
        return 'No saved schedule found for ' . $subject . '.';
    }

    $segments = [];
    foreach ($entries as $entry) {
        $day = trim((string) ($entry['day'] ?? ''));
        $time = trim((string) ($entry['time'] ?? ''));
        $room = trim((string) ($entry['room'] ?? ''));
        $professor = trim((string) ($entry['professor_name'] ?? ''));

        $day = $day !== '' ? $day : 'unspecified day';
        $time = $time !== '' ? $time : 'unspecified time';
        $room = $room !== '' ? $room : 'unspecified room';
        $professor = $professor !== '' ? $professor : 'an assigned instructor';

        $segments[] = $day . ' at ' . $time . ' in ' . $room . ' with ' . $professor;
    }

    return 'Here is the schedule for ' . $subject . ': ' . implode('; ', $segments) . '.';
}

function expandQueryTerms(string $query): array {
    $normalized = normalizeText($query);
    $terms = array_filter(explode(' ', $normalized));

    $synonyms = [
        'schedule' => ['class', 'classes', 'timetable', 'time', 'when'],
        'professor' => ['teacher', 'instructor', 'faculty'],
        'expertise' => ['specialization', 'specialty', 'focus', 'skills'],
        'subject' => ['course', 'topic'],
        'login' => ['sign', 'access'],
        'database' => ['db'],
    ];

    foreach ($synonyms as $anchor => $related) {
        if (str_contains($normalized, $anchor)) {
            $terms = array_merge($terms, $related);
            continue;
        }

        foreach ($related as $alt) {
            if (str_contains($normalized, $alt)) {
                $terms[] = $anchor;
                $terms = array_merge($terms, $related);
                break;
            }
        }
    }

    $terms = array_unique(array_filter($terms));
    return array_values($terms);
}

function buildSnippet(string $text, array $terms): string {
    $plain = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    if ($plain === '') {
        return '';
    }

    $lower = strtolower($plain);
    $position = false;
    foreach ($terms as $term) {
        $term = strtolower($term);
        $termPos = strpos($lower, $term);
        if ($termPos !== false) {
            $position = $termPos;
            break;
        }
    }

    if ($position === false) {
        $snippet = mb_substr($plain, 0, 240);
        return $snippet . (strlen($plain) > 240 ? '...' : '');
    }

    $start = max(0, $position - 120);
    $snippet = mb_substr($plain, $start, 240);
    if ($start > 0) {
        $snippet = '...' . ltrim($snippet);
    }
    if ($start + 240 < strlen($plain)) {
        $snippet = rtrim($snippet) . '...';
    }
    return $snippet;
}

function updateConversationContext(string $query, string $action, array $data = []): void {
    if (!isset($_SESSION['conversation_context'])) {
        $_SESSION['conversation_context'] = [
            'recent_queries' => [],
            'mentioned_professors' => [],
            'searched_subjects' => [],
            'last_action' => null,
            'conversation_flow' => []
        ];
    }

    $_SESSION['conversation_context']['recent_queries'][] = [
        'query' => $query,
        'timestamp' => time()
    ];

    // Keep only last 10 queries
    if (count($_SESSION['conversation_context']['recent_queries']) > 10) {
        $_SESSION['conversation_context']['recent_queries'] = array_slice(
            $_SESSION['conversation_context']['recent_queries'], -10
        );
    }

    if (isset($data['professor_name'])) {
        if (!in_array($data['professor_name'], $_SESSION['conversation_context']['mentioned_professors'])) {
            $_SESSION['conversation_context']['mentioned_professors'][] = $data['professor_name'];
        }
    }

    if (isset($data['subject'])) {
        if (!in_array($data['subject'], $_SESSION['conversation_context']['searched_subjects'])) {
            $_SESSION['conversation_context']['searched_subjects'][] = $data['subject'];
        }
    }

    $_SESSION['conversation_context']['last_action'] = $action;
    $_SESSION['conversation_context']['conversation_flow'][] = [
        'action' => $action,
        'timestamp' => time()
    ];

    // Keep only last 20 flow items
    if (count($_SESSION['conversation_context']['conversation_flow']) > 20) {
        $_SESSION['conversation_context']['conversation_flow'] = array_slice(
            $_SESSION['conversation_context']['conversation_flow'], -20
        );
    }
}

function generateSmartSuggestions(array $context, string $lastAction, array $currentData = []): array {
    $suggestions = [];

    // Context-aware suggestions based on last action
    switch ($lastAction) {
        case 'professor_profile':
            if (!empty($currentData['professor_name'])) {
                $suggestions[] = [
                    'text' => "Show {$currentData['professor_name']}'s schedule",
                    'icon' => 'fa-calendar',
                    'type' => 'schedule'
                ];
                if (!empty($currentData['expertise'])) {
                    $suggestions[] = [
                        'text' => "Find other professors with similar expertise",
                        'icon' => 'fa-users',
                        'type' => 'expertise'
                    ];
                }
            }
            break;

        case 'subject_lookup':
            if (!empty($currentData['subject'])) {
                $suggestions[] = [
                    'text' => "Show full schedule for {$currentData['subject']}",
                    'icon' => 'fa-calendar-alt',
                    'type' => 'schedule'
                ];
            }
            break;

        case 'professor_schedule':
            $suggestions[] = [
                'text' => "Show all professor schedules",
                'icon' => 'fa-list',
                'type' => 'list_all'
            ];
            break;

        case 'list_all':
            $suggestions[] = [
                'text' => "Search by specific subject",
                'icon' => 'fa-search',
                'type' => 'search'
            ];
            break;
    }

    // Add generic helpful suggestions
    if (count($suggestions) < 3) {
        $generic = [
            ['text' => 'Who teaches AI?', 'icon' => 'fa-robot', 'type' => 'subject'],
            ['text' => 'Show all professors', 'icon' => 'fa-users', 'type' => 'list'],
            ['text' => 'Find experts in database', 'icon' => 'fa-database', 'type' => 'expertise']
        ];

        foreach ($generic as $g) {
            if (count($suggestions) >= 3) break;
            $suggestions[] = $g;
        }
    }

    return $suggestions;
}

function runKnowledgeSearch(string $query): array {
    $query = trim($query);
    if ($query === '') {
        return [];
    }

    $files = [
        'README.md',
        'AI_CHATBOT_FEATURES.md',
        'KEYWORD_SCANNING_SYSTEM.md',
        'REDESIGN_SUMMARY.md',
        'PROJECT_COMPLETE.md',
        'CHATGPT_STYLE_UPDATE.md',
        'LARGE_SCREEN_IMPROVEMENTS.md',
    ];

    $terms = expandQueryTerms($query);
    $matches = [];

    foreach ($files as $file) {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
        if (!file_exists($path)) {
            continue;
        }

        $content = @file_get_contents($path);
        if ($content === false) {
            continue;
        }

        $sections = preg_split('/\n(?=#+\s)/', $content);
        foreach ($sections as $section) {
            if (trim($section) === '') {
                continue;
            }

            $sectionLower = strtolower($section);
            $score = 0;

            foreach ($terms as $term) {
                $term = trim($term);
                if ($term === '') {
                    continue;
                }
                $frequency = substr_count($sectionLower, $term);
                if ($frequency > 0) {
                    $score += $frequency * 3;
                }
            }

            if (str_contains($sectionLower, strtolower($query))) {
                $score += 5;
            }

            if ($score === 0) {
                continue;
            }

            $title = '';
            if (preg_match('/^#+\s*(.+)$/m', $section, $matchesTitle)) {
                $title = trim($matchesTitle[1]);
            }

            $matches[] = [
                'file' => $file,
                'title' => $title,
                'score' => $score,
                'snippet' => buildSnippet($section, $terms)
            ];
        }
    }

    usort($matches, function ($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    return $matches;
}

if (isset($_GET['suggest'])) {
    $query = trim((string) ($_GET['suggest'] ?? ''));
    if ($query === '') {
        respondJson([]);
    }

    $stmt = $conn->prepare("SELECT DISTINCT professor_name FROM professors WHERE professor_name LIKE CONCAT('%', ?, '%') LIMIT 5");
    if (!$stmt) {
        respondJson([]);
    }

    $stmt->bind_param('s', $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $names = [];
    while ($row = $result->fetch_assoc()) {
        $names[] = $row['professor_name'];
    }
    $stmt->close();

    respondJson($names);
}

$action = $_GET['action'] ?? null;

if ($action === 'detect_intent') {
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    
    if (empty($query)) {
        respondJson(['error' => 'Query is required']);
    }
    
    // Detect intent from database patterns
    $intent = detectIntentFromDB($conn, $query);
    
    // Detect sentiment
    $sentiment = detectSentimentSimple($query);
    
    // Get response from database
    $response = getResponseFromDB($conn, $intent, $sentiment);
    
    // Log the conversation
    saveConversationToDB($conn, $query, $response ?? 'No response found', [
        'intent' => $intent,
        'sentiment' => $sentiment
    ]);
    
    respondJson([
        'intent' => $intent,
        'sentiment' => $sentiment,
        'response' => $response,
        'query' => $query
    ]);
}

if ($action === 'get_suggestions') {
    // Fetch unique subjects from schedules
    $subjects = [];
    $schedules = [];
    
    $subjectSql = "SELECT DISTINCT subject FROM schedules WHERE subject IS NOT NULL AND subject != '' ORDER BY subject LIMIT 5";
    $result = $conn->query($subjectSql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row['subject'];
        }
    }
    
    // Fetch schedule count
    $scheduleSql = "SELECT COUNT(*) as count FROM schedules";
    $result = $conn->query($scheduleSql);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['count'] > 0) {
            $schedules[] = ['count' => $row['count']];
        }
    }
    
    respondJson([
        'subjects' => $subjects,
        'schedules' => $schedules
    ]);
}

if ($action === 'session_status') {
    $isActive = isset($_SESSION['role']) && $_SESSION['role'] !== '';
    respondJson([
        'active' => $isActive,
        'timestamp' => time(),
    ]);
}

if ($action === 'list_schedules') {
    $name = isset($_GET['name']) ? trim($_GET['name']) : '';
    $schedules = [];

    if ($name !== '') {
        $sql = "SELECT p.professor_name, p.photo, p.plantilla_title, s.subject, s.day, s.time, s.room, s.schedule_file
                FROM schedules s
                JOIN professors p ON p.professor_id = s.professor_id
                WHERE p.professor_name LIKE CONCAT('%', ?, '%')
                ORDER BY p.professor_name, s.day, s.time";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $schedules[] = $row;
            }
            $stmt->close();
        }
    } else {
        $sql = "SELECT p.professor_name, p.photo, p.plantilla_title, s.subject, s.day, s.time, s.room, s.schedule_file
                FROM schedules s
                JOIN professors p ON p.professor_id = s.professor_id
                ORDER BY p.professor_name, s.day, s.time";
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $schedules[] = $row;
            }
        }
    }

    respondJson(['schedules' => $schedules]);
}

if ($action === 'knowledge') {
    $query = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
    $matches = runKnowledgeSearch($query);
    respondJson(['matches' => array_slice($matches, 0, 3)]);
}

if ($action === 'list_subjects') {
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 6;
    $subjects = fetchDistinctSubjects($conn, $limit);
    respondJson(['subjects' => $subjects]);
}

if ($action === 'ask') {
    $query = trim((string) ($_GET['query'] ?? $_GET['q'] ?? ''));
    if ($query === '') {
        respondJson([
            'type' => 'error',
            'message' => 'Please provide a question to process.'
        ]);
    }

    // Analyze message intent and sentiment
    $startTime = microtime(true);
    
    $userIntent = detectIntentFromDB($conn, $query);
    $userSentiment = detectSentimentSimple($query);
    $userTone = 'neutral';
    
    error_log("🤖 AI Analysis - Intent: $userIntent | Sentiment: $userSentiment | Tone: $userTone");
    
    // Handle conversational intents
    $conversationalIntents = ['flirt', 'gratitude', 'sadness', 'anger', 'joke', 'small_talk', 'help'];
    
    if (in_array($userIntent, $conversationalIntents)) {
        // Get response from database
        $response = getResponseFromDB($conn, $userIntent, $userSentiment);
        
        if ($response) {
            // Calculate response time
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            // Save conversation to database
            saveConversationToDB($conn, $query, $response, [
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'response_time_ms' => $responseTime
            ]);
            
            // Generate suggestions based on intent
            $suggestions = [];
            switch ($userIntent) {
                case 'flirt':
                case 'small_talk':
                    $suggestions = ['Show all professors', 'What subjects are available?', 'Find AI experts'];
                    break;
                case 'gratitude':
                    $suggestions = ['Find another professor', 'Check more schedules', 'Search by subject'];
                    break;
                case 'sadness':
                case 'anger':
                    $suggestions = ['Show all professors', 'Find a helpful resource', 'View schedules'];
                    break;
                case 'joke':
                    $suggestions = ['Search for professors', 'View schedules', 'Find experts'];
                    break;
                case 'help':
                    $suggestions = ['Show all professors', 'Who teaches Database?', 'Find AI experts'];
                    break;
            }
            
            respondJson([
                'type' => 'conversational',
                'answer' => $response,
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'response_time_ms' => $responseTime,
                'suggestions' => $suggestions,
                'source' => 'database'
            ]);
        }
    }

    $lowerQuery = strtolower($query);

    $thankYouTerms = ['thanks', 'thank you', 'thankyou', 'ty'];
    foreach ($thankYouTerms as $term) {
        if ($lowerQuery === $term) {
            $response = "You're welcome! I'm glad I could assist. If you have a moment, could you please rate your experience with me today?";
            
            // Save conversation
            saveConversationToDB($conn, $query, $response, [
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'type' => 'survey'
            ]);
            
            respondJson([
                'type' => 'survey',
                'answer' => $response,
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'actions' => [
                    ['label' => '👍 Excellent', 'value' => '5'],
                    ['label' => '🙂 Good', 'value' => '4'],
                    ['label' => '😐 Okay', 'value' => '3'],
                    ['label' => '👎 Bad', 'value' => '2'],
                ]
            ]);
        }
    }

    $professorIndex = buildProfessorIndex($conn);
    $subjectIndex = buildSubjectIndex($conn);
    $professorMatch = findBestMatch($query, $professorIndex, 0.52);
    $subjectMatch = findBestMatch($query, $subjectIndex, 0.45);

    $scheduleKeywords = ['schedule', 'class', 'classes', 'time', 'when', 'where', 'timetable'];
    $expertiseKeywords = ['expertise', 'specialize', 'specialization', 'specialty', 'focus', 'background', 'bio', 'about'];
    $subjectKeywords = ['subject', 'teach', 'teacher', 'instructor', 'handles', 'handle', 'teaches'];

    $scheduleIntent = containsAny($query, $scheduleKeywords);
    $subjectIntent = containsAny($query, $subjectKeywords);
    $expertiseIntent = containsAny($query, $expertiseKeywords);

    if ($professorMatch && $professorMatch['score'] >= 0.6 && $scheduleIntent) {
        $professor = loadProfessorByName($conn, $professorMatch['label']);
        if ($professor) {
            $professor = hydrateProfessorRow($conn, $professor);
            $answer = composeScheduleAnswer($professor['professor_name'], $professor['schedules']);
            
            updateConversationContext($query, 'professor_schedule', [
                'professor_name' => $professor['professor_name']
            ]);
            
            $suggestions = generateSmartSuggestions(
                $_SESSION['conversation_context'],
                'professor_schedule',
                ['professor_name' => $professor['professor_name']]
            );
            
            // Save conversation
            saveConversationToDB($conn, $query, $answer, [
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'action' => 'professor_schedule',
                'professor_name' => $professor['professor_name']
            ]);
            
            respondJson([
                'type' => 'professor_schedule',
                'query' => $query,
                'professor' => $professor,
                'answer' => $answer,
                'sources' => ['database:professors', 'database:schedules'],
                'suggestions' => $suggestions,
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'context_hint' => "💡 You can ask about this professor's expertise or find similar professors"
            ]);
        }
    }

    if ($subjectMatch && $subjectMatch['score'] >= 0.48 && $scheduleIntent) {
        $subject = $subjectMatch['label'];
        $subjectSchedules = getSchedulesBySubject($conn, $subject);
        $answer = composeSubjectScheduleAnswer($subject, $subjectSchedules);
        
        updateConversationContext($query, 'subject_schedule', ['subject' => $subject]);
        
        $suggestions = generateSmartSuggestions(
            $_SESSION['conversation_context'],
            'subject_schedule',
            ['subject' => $subject]
        );
        
        // Save conversation
        saveConversationToDB($conn, $query, $answer, [
            'intent' => $userIntent,
            'sentiment' => $userSentiment,
            'tone' => $userTone,
            'action' => 'subject_schedule',
            'subject' => $subject
        ]);
        
        respondJson([
            'type' => 'subject_schedule',
            'query' => $query,
            'subject' => $subject,
            'schedules' => $subjectSchedules,
            'answer' => $answer,
            'sources' => ['database:schedules', 'database:professors'],
            'suggestions' => $suggestions,
            'intent' => $userIntent,
            'sentiment' => $userSentiment,
            'tone' => $userTone,
            'context_hint' => "📚 Want to know more? Ask about the professors teaching this subject"
        ]);
    }

    if ($subjectMatch && $subjectMatch['score'] >= 0.48 && $subjectIntent) {
        $subject = $subjectMatch['label'];
        $stmt = $conn->prepare("SELECT DISTINCT p.* FROM professors p JOIN schedules s ON p.professor_id = s.professor_id WHERE s.subject LIKE CONCAT('%', ?, '%')");
        if ($stmt) {
            $stmt->bind_param('s', $subject);
            $stmt->execute();
            $result = $stmt->get_result();
            $professors = [];
            while ($row = $result->fetch_assoc()) {
                $professors[] = hydrateProfessorRow($conn, $row);
            }
            $stmt->close();

            if (!empty($professors)) {
                $names = array_map(static function ($prof) {
                    return $prof['professor_name'];
                }, $professors);

                $answer = 'The following instructors teach ' . $subject . ': ' . implode(', ', $names) . '.';
                
                updateConversationContext($query, 'subject_lookup', ['subject' => $subject]);
                
                $suggestions = generateSmartSuggestions(
                    $_SESSION['conversation_context'],
                    'subject_lookup',
                    ['subject' => $subject]
                );
                
                // Save conversation
                saveConversationToDB($conn, $query, $answer, [
                    'intent' => $userIntent,
                    'sentiment' => $userSentiment,
                    'tone' => $userTone,
                    'action' => 'subject_lookup',
                    'subject' => $subject,
                    'professors_count' => count($professors)
                ]);
                
                respondJson([
                    'type' => 'subject_lookup',
                    'query' => $query,
                    'subject' => $subject,
                    'professors' => $professors,
                    'answer' => $answer,
                    'sources' => ['database:professors', 'database:schedules'],
                    'suggestions' => $suggestions,
                    'intent' => $userIntent,
                    'sentiment' => $userSentiment,
                    'tone' => $userTone,
                    'context_hint' => "🎯 You can ask about any of these professors' full details or schedules"
                ]);
            }
        }
    }

    if ($professorMatch && ($expertiseIntent || !$scheduleIntent) && $professorMatch['score'] >= 0.5) {
        $professor = loadProfessorByName($conn, $professorMatch['label']);
        if ($professor) {
            $professor = hydrateProfessorRow($conn, $professor);
            $bio = trim((string) ($professor['bio'] ?? ''));
            $expertise = trim((string) ($professor['expertise'] ?? ''));
            $answerParts = [];
            if ($bio !== '') {
                $answerParts[] = $bio;
            }
            if ($expertise !== '') {
                $answerParts[] = 'Key expertise areas include ' . $expertise . '.';
            }

            $answer = !empty($answerParts)
                ? implode(' ', $answerParts)
                : $professor['professor_name'] . ' is in the system, but no biography or expertise details are available yet.';

            updateConversationContext($query, 'professor_profile', [
                'professor_name' => $professor['professor_name'],
                'expertise' => $expertise
            ]);
            
            $suggestions = generateSmartSuggestions(
                $_SESSION['conversation_context'],
                'professor_profile',
                [
                    'professor_name' => $professor['professor_name'],
                    'expertise' => $expertise
                ]
            );

            // Save conversation
            saveConversationToDB($conn, $query, $answer, [
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'action' => 'professor_profile',
                'professor_name' => $professor['professor_name']
            ]);

            respondJson([
                'type' => 'professor_profile',
                'query' => $query,
                'professor' => $professor,
                'answer' => $answer,
                'sources' => ['database:professors', 'database:schedules'],
                'suggestions' => $suggestions,
                'intent' => $userIntent,
                'sentiment' => $userSentiment,
                'tone' => $userTone,
                'context_hint' => "✨ Want to know more? Ask about their schedule or find similar experts"
            ]);
        }
    }

    $matches = runKnowledgeSearch($query);
    if (!empty($matches)) {
        $top = $matches[0];
        $answer = 'Here is what I found related to "' . $query . '": ' . ($top['snippet'] ?? '');
        
        // Save conversation
        saveConversationToDB($conn, $query, $answer, [
            'intent' => $userIntent,
            'sentiment' => $userSentiment,
            'tone' => $userTone,
            'action' => 'knowledge_search',
            'source' => $top['file']
        ]);
        
        respondJson([
            'type' => 'knowledge',
            'query' => $query,
            'answer' => $answer,
            'matches' => array_slice($matches, 0, 3),
            'intent' => $userIntent,
            'sentiment' => $userSentiment,
            'tone' => $userTone,
            'sources' => array_values(array_unique(array_map(static function ($match) {
                return 'docs:' . $match['file'];
            }, array_slice($matches, 0, 3))))
        ]);
    }

    // Save no match conversation
    $noMatchMsg = "I couldn't find anything relevant. Try rephrasing or sharing more details.";
    saveConversationToDB($conn, $query, $noMatchMsg, [
        'intent' => $userIntent,
        'sentiment' => $userSentiment,
        'tone' => $userTone,
        'action' => 'no_match'
    ]);

    respondJson([
        'type' => 'no_match',
        'query' => $query,
        'message' => $noMatchMsg,
        'intent' => $userIntent,
        'sentiment' => $userSentiment,
        'tone' => $userTone
    ]);
}

// Get conversational response from database
if ($action === 'get_response') {
    $intent = $_GET['intent'] ?? 'unknown';
    $sentiment = $_GET['sentiment'] ?? 'neutral';
    
    error_log("🎯 Fetching response for intent: $intent, sentiment: $sentiment");
    
    $response = getResponseFromDB($conn, $intent, $sentiment);
    
    if ($response) {
        // Save conversation
        saveConversationToDB($conn, "Conversational query", $response, [
            'intent' => $intent,
            'sentiment' => $sentiment,
            'tone' => 'friendly',
            'action' => 'conversational_response'
        ]);
        
        respondJson([
            'response' => $response,
            'intent' => $intent,
            'sentiment' => $sentiment,
            'source' => 'database'
        ]);
    } else {
        respondJson([
            'response' => null,
            'intent' => $intent,
            'sentiment' => $sentiment,
            'error' => 'No matching response found'
        ]);
    }
}

if ($action === 'list_all') {
    $result = $conn->query("SELECT * FROM professors ORDER BY professor_name");
    $professors = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $professors[] = hydrateProfessorRow($conn, $row);
        }
    }

    updateConversationContext('list all professors', 'list_all', []);
    
    $suggestions = [
        ['text' => 'Search by expertise', 'icon' => 'fa-brain', 'type' => 'expertise'],
        ['text' => 'Find by subject', 'icon' => 'fa-book', 'type' => 'subject'],
        ['text' => 'View schedules', 'icon' => 'fa-calendar', 'type' => 'schedule']
    ];

    respondJson([
        'professors' => $professors,
        'suggestions' => $suggestions,
        'context_hint' => "🔍 Click on any professor to see details, or search by expertise/subject"
    ]);
}

if ($action === 'find_by_subject') {
    $subject = $_GET['subject'] ?? '';
    $sql = "SELECT DISTINCT p.* FROM professors p 
            JOIN schedules s ON p.professor_id = s.professor_id 
            WHERE s.subject LIKE CONCAT('%', ?, '%')";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $subject);
        $stmt->execute();
        $result = $stmt->get_result();

        $professors = [];
        while ($row = $result->fetch_assoc()) {
            $professors[] = hydrateProfessorRow($conn, $row);
        }
        $stmt->close();

        updateConversationContext("find by subject: $subject", 'find_by_subject', ['subject' => $subject]);
        
        $suggestions = [
            ['text' => "View $subject schedule", 'icon' => 'fa-calendar', 'type' => 'schedule'],
            ['text' => 'Find other subjects', 'icon' => 'fa-search', 'type' => 'search'],
            ['text' => 'Show all professors', 'icon' => 'fa-users', 'type' => 'list']
        ];

        respondJson([
            'professors' => $professors,
            'suggestions' => $suggestions,
            'context_hint' => "📖 Found professors teaching $subject. Ask about their schedules or expertise!"
        ]);
    }

    respondJson(['professors' => []]);
}

if ($action === 'find_by_expertise') {
    $expertise = $_GET['expertise'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM professors WHERE expertise LIKE CONCAT('%', ?, '%')");
    if ($stmt) {
        $stmt->bind_param('s', $expertise);
        $stmt->execute();
        $result = $stmt->get_result();

        $professors = [];
        while ($row = $result->fetch_assoc()) {
            $professors[] = hydrateProfessorRow($conn, $row);
        }
        $stmt->close();

        updateConversationContext("find by expertise: $expertise", 'find_by_expertise', ['expertise' => $expertise]);
        
        $suggestions = [
            ['text' => "Show their schedules", 'icon' => 'fa-calendar-alt', 'type' => 'schedule'],
            ['text' => 'Search other expertise', 'icon' => 'fa-brain', 'type' => 'expertise'],
            ['text' => 'View all professors', 'icon' => 'fa-list', 'type' => 'list']
        ];

        respondJson([
            'professors' => $professors,
            'suggestions' => $suggestions,
            'context_hint' => "🎓 These experts specialize in $expertise. Want to see their full profiles or schedules?"
        ]);
    }

    respondJson(['professors' => []]);
}

if ($action !== null) {
    respondJson(["error" => "No valid action handler found."]);
}

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $stmt = $conn->prepare("SELECT * FROM professors WHERE professor_name LIKE CONCAT('%', ?, '%')");
    if ($stmt) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();

        $professors = [];
        while ($row = $result->fetch_assoc()) {
            $professors[] = hydrateProfessorRow($conn, $row);
        }
        $stmt->close();

        if (count($professors) > 0) {
            respondJson(['professors' => $professors]);
        }

        respondJson(['response' => "I couldn't find any professor with that name. Try searching for something else!"]);
    }

    respondJson(['response' => "I couldn't find any professor with that name. Try searching for something else!"]);
}

respondJson(["error" => "No valid request parameters."]);
?>
