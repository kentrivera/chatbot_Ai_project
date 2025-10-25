<?php
session_start();
// Ensure only admins can add schedules
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: professors.php');
    exit();
}

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'chatbot';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // Fallback: redirect without success
    header('Location: professors.php');
    exit();
}

// Collect and validate inputs
$professor_id = isset($_POST['professor_id']) ? intval($_POST['professor_id']) : 0;
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$day = isset($_POST['day']) ? trim($_POST['day']) : '';
$time = isset($_POST['time']) ? trim($_POST['time']) : '';
$room = isset($_POST['room']) ? trim($_POST['room']) : '';

// Optional file upload handling
$schedule_file_path = null;
if (isset($_FILES['schedule_file']) && is_array($_FILES['schedule_file']) && $_FILES['schedule_file']['error'] === UPLOAD_ERR_OK) {
    $allowed_ext = ['pdf','doc','docx','jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($_FILES['schedule_file']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed_ext, true)) {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'schedules';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $_FILES['schedule_file']['name']);
        $destAbs = $dir . DIRECTORY_SEPARATOR . $safeName;
        if (@move_uploaded_file($_FILES['schedule_file']['tmp_name'], $destAbs)) {
            // Store relative path for web access
            $schedule_file_path = 'schedules/' . $safeName;
        }
    }
}

if ($professor_id <= 0 || $subject === '' || $day === '' || $time === '' || $room === '') {
    header('Location: professors.php');
    exit();
}

// Get professor name to store redundantly (per schema)
$prof_name = '';
if ($stmt = $conn->prepare('SELECT professor_name FROM professors WHERE professor_id = ?')) {
    $stmt->bind_param('i', $professor_id);
    $stmt->execute();
    $stmt->bind_result($prof_name);
    $stmt->fetch();
    $stmt->close();
}

if ($prof_name === '') {
    // Invalid professor id
    header('Location: professors.php');
    exit();
}

// Insert schedule (include schedule_file if present)
if ($schedule_file_path) {
    if ($stmt = $conn->prepare('INSERT INTO schedules (professor_name, subject, day, time, room, professor_id, schedule_file) VALUES (?, ?, ?, ?, ?, ?, ?)')) {
        $stmt->bind_param('sssssis', $prof_name, $subject, $day, $time, $room, $professor_id, $schedule_file_path);
        $stmt->execute();
        $stmt->close();
    }
} else {
    if ($stmt = $conn->prepare('INSERT INTO schedules (professor_name, subject, day, time, room, professor_id) VALUES (?, ?, ?, ?, ?, ?)')) {
        $stmt->bind_param('sssssi', $prof_name, $subject, $day, $time, $room, $professor_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();

// Redirect with success flag
header('Location: professors.php?sched_success=1');
exit();
?>
