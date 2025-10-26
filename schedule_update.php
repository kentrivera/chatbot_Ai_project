<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
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
    header('Location: professors.php');
    exit();
}

$schedule_id = isset($_POST['schedule_id']) ? (int) $_POST['schedule_id'] : 0;
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$day = isset($_POST['day']) ? trim($_POST['day']) : '';
$time = isset($_POST['time']) ? trim($_POST['time']) : '';
$room = isset($_POST['room']) ? trim($_POST['room']) : '';
$professor_id = isset($_POST['professor_id']) ? (int) $_POST['professor_id'] : 0;
$remove_file = isset($_POST['remove_file']);

if ($schedule_id <= 0 || $subject === '' || $day === '' || $time === '' || $room === '') {
    header('Location: professors.php');
    exit();
}

$currentSchedule = null;
if ($stmt = $conn->prepare('SELECT id, professor_id, professor_name, schedule_file FROM schedules WHERE id = ?')) {
    $stmt->bind_param('i', $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentSchedule = $result->fetch_assoc();
    $stmt->close();
}

if (!$currentSchedule) {
    $conn->close();
    header('Location: professors.php');
    exit();
}

if ($professor_id <= 0) {
    $professor_id = (int) ($currentSchedule['professor_id'] ?? 0);
}

$professor_name = $currentSchedule['professor_name'] ?? '';
if ($professor_id > 0) {
    if ($stmt = $conn->prepare('SELECT professor_name FROM professors WHERE professor_id = ?')) {
        $stmt->bind_param('i', $professor_id);
        $stmt->execute();
        $stmt->bind_result($prof_name_temp);
        if ($stmt->fetch()) {
            $professor_name = $prof_name_temp;
        }
        $stmt->close();
    }
}

$schedule_file_path = $currentSchedule['schedule_file'] ?? '';
$old_file_path = $schedule_file_path;

if (isset($_FILES['schedule_file']) && is_array($_FILES['schedule_file']) && $_FILES['schedule_file']['error'] === UPLOAD_ERR_OK) {
    $allowed_ext = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['schedule_file']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed_ext, true)) {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'schedules';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $_FILES['schedule_file']['name']);
        $destAbs = $dir . DIRECTORY_SEPARATOR . $safeName;
        if (@move_uploaded_file($_FILES['schedule_file']['tmp_name'], $destAbs)) {
            $schedule_file_path = 'schedules/' . $safeName;
        }
    }
}

if ($remove_file && empty($_FILES['schedule_file']['name'])) {
    $schedule_file_path = '';
}

if ($schedule_file_path === '') {
    $schedule_file_path = null;
}

if ($old_file_path && ($schedule_file_path === null || $schedule_file_path !== $old_file_path)) {
    $absoluteOldPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . $old_file_path);
    $baseDir = realpath(__DIR__);
    if ($absoluteOldPath && $baseDir && strpos($absoluteOldPath, $baseDir) === 0) {
        @unlink($absoluteOldPath);
    }
}

if ($schedule_file_path && $schedule_file_path !== $old_file_path) {
    // ensure forward slashes for web usage
    $schedule_file_path = str_replace('\\', '/', $schedule_file_path);
}

if ($stmt = $conn->prepare('UPDATE schedules SET subject = ?, day = ?, time = ?, room = ?, professor_id = ?, professor_name = ?, schedule_file = ? WHERE id = ?')) {
    $schedule_file_param = $schedule_file_path;
    $stmt->bind_param('ssssissi', $subject, $day, $time, $room, $professor_id, $professor_name, $schedule_file_param, $schedule_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header('Location: professors.php?sched_updated=1');
exit();
?>