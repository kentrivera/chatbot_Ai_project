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

$schedule_id = isset($_POST['schedule_id']) ? (int) $_POST['schedule_id'] : 0;
if ($schedule_id <= 0) {
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

$schedule = null;
if ($stmt = $conn->prepare('SELECT schedule_file FROM schedules WHERE id = ?')) {
    $stmt->bind_param('i', $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();
}

if (!$schedule) {
    $conn->close();
    header('Location: professors.php');
    exit();
}

$schedule_file = $schedule['schedule_file'] ?? null;

if ($stmt = $conn->prepare('DELETE FROM schedules WHERE id = ?')) {
    $stmt->bind_param('i', $schedule_id);
    $stmt->execute();
    $stmt->close();
}

if ($schedule_file) {
    if ($stmt = $conn->prepare('SELECT COUNT(*) FROM schedules WHERE schedule_file = ?')) {
        $stmt->bind_param('s', $schedule_file);
        $stmt->execute();
        $stmt->bind_result($file_count);
        $stmt->fetch();
        $stmt->close();

        if ((int) $file_count === 0) {
            $absolutePath = realpath(__DIR__ . DIRECTORY_SEPARATOR . $schedule_file);
            $baseDir = realpath(__DIR__);
            if ($absolutePath && $baseDir && strpos($absolutePath, $baseDir) === 0 && is_file($absolutePath)) {
                @unlink($absolutePath);
            }
        }
    }
}

$conn->close();

header('Location: professors.php?sched_deleted=1');
exit();
?>