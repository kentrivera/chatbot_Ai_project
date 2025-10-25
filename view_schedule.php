<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "chatbot");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $schedule_id = (int)$_GET['id'];
    $result = $conn->query("SELECT schedule_file FROM schedules WHERE id = $schedule_id");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = $row['schedule_file'];
        
        if ($file_path && file_exists($file_path)) {
            $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
            
            // Set appropriate content type
            switch ($file_extension) {
                case 'pdf':
                    header('Content-Type: application/pdf');
                    break;
                case 'doc':
                    header('Content-Type: application/msword');
                    break;
                case 'docx':
                    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                    break;
                case 'jpg':
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'png':
                    header('Content-Type: image/png');
                    break;
                default:
                    header('Content-Type: application/octet-stream');
            }
            
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit();
        }
    }
}

echo "File not found";
$conn->close();
?>
