<?php
$conn = new mysqli("localhost", "root", "", "chatbot");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // Delete associated schedules first
    $conn->query("DELETE FROM schedules WHERE professor_id = $id");

    // Then delete the professor
    $conn->query("DELETE FROM professors WHERE professor_id = $id");

    header("Location: admin.php?deleted=1");
    exit();
}

$conn->close();
?>
