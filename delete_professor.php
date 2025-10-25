<?php
$conn = new mysqli("localhost", "root", "", "chatbot");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

// Delete the professor's record
$sql = "DELETE FROM professors WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: admin.php?success=3");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
