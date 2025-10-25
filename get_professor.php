<?php
$conn = new mysqli("localhost", "root", "", "chatbot");
$id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM professors WHERE professor_id = $id");
$professor = $result->fetch_assoc();

// Check if schedules are requested
if (isset($_GET['schedules'])) {
    $sched_result = $conn->query("SELECT * FROM schedules WHERE professor_id = $id ORDER BY `day`, `time`");
    $schedules = array();
    while ($row = $sched_result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $professor['schedules'] = $schedules;
}

echo json_encode($professor);
$conn->close();
?>
