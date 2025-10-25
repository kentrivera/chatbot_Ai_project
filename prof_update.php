<?php
$conn = new mysqli("localhost", "root", "", "chatbot");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $expertise = $conn->real_escape_string($_POST['expertise']);
    $academic = $conn->real_escape_string($_POST['academic']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $age = intval($_POST['age']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $civil_status = $conn->real_escape_string($_POST['civil_status']);
    $plantilla_title = $conn->real_escape_string($_POST['plantilla_title']);
    $years_in_service = intval($_POST['years_in_service']);
    $exam_administered = $conn->real_escape_string($_POST['exam_administered']);
    $course_program = $conn->real_escape_string($_POST['course_program']);
    $highest_educ_attainment = $conn->real_escape_string($_POST['highest_educ_attainment']);

    // Update SQL query
    $sql = "UPDATE professors SET 
                professor_name = '$name',
                bio = '$bio',
                expertise = '$expertise',
                academic_distinctions = '$academic',
                sex = '$sex',
                age = $age,
                birthdate = '$birthdate',
                civil_status = '$civil_status',
                plantilla_title = '$plantilla_title',
                years_in_service = $years_in_service,
                exam_administered = '$exam_administered',
                course_program = '$course_program',
                highest_educ_attainment = '$highest_educ_attainment'
            WHERE professor_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?success=2");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
