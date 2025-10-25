<?php
session_start();
$conn = new mysqli("localhost", "root", "", "chatbot");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $conn->real_escape_string($_POST['name']);
$bio = $conn->real_escape_string($_POST['bio']);
$expertise = $conn->real_escape_string($_POST['expertise']);
$academic = $conn->real_escape_string($_POST['academic']);

$age = (int)$_POST['age'];
$sex = $conn->real_escape_string($_POST['sex']);
$birthdate = $conn->real_escape_string($_POST['birthdate']);
$civil_status = $conn->real_escape_string($_POST['civil_status']);
$plantilla_title = $conn->real_escape_string($_POST['plantilla_title']);
$years_in_service = (int)$_POST['years_in_service'];
$exam_administered = $conn->real_escape_string($_POST['exam_administered']);
$course_program = $conn->real_escape_string($_POST['course_program']);
$highest_educ_attainment = $conn->real_escape_string($_POST['highest_educ_attainment']);

// Process schedule file upload if provided
$schedule_file_path = null;
if (isset($_FILES['schedule_file']) && $_FILES['schedule_file']['error'] === 0) {
    $schedule_dir = "schedules/";
    if (!file_exists($schedule_dir)) {
        mkdir($schedule_dir, 0777, true);
    }
    $schedule_filename = time() . "_" . basename($_FILES["schedule_file"]["name"]);
    $schedule_file_path = $schedule_dir . $schedule_filename;
    move_uploaded_file($_FILES["schedule_file"]["tmp_name"], $schedule_file_path);
}

// Process the multiple schedule inputs
$subjects = isset($_POST['subject']) ? $_POST['subject'] : [];
$days = isset($_POST['day']) ? $_POST['day'] : [];
$times = isset($_POST['time']) ? $_POST['time'] : [];
$rooms = isset($_POST['room']) ? $_POST['room'] : [];

// File upload check for professor photo
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $target_dir = "Images/";
    $filename = time() . "_" . basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        // Insert into professors table
        $sql = "INSERT INTO professors (
            professor_name, age, sex, birthdate, civil_status, plantilla_title,
            years_in_service, exam_administered, course_program, highest_educ_attainment, 
            bio, expertise, academic_distinctions, photo
        ) VALUES (
            '$name', '$age', '$sex', '$birthdate', '$civil_status', '$plantilla_title',
            '$years_in_service', '$exam_administered', '$course_program', '$highest_educ_attainment', 
            '$bio', '$expertise', '$academic','$target_file'
        )";

        if ($conn->query($sql) === TRUE) {
            $professor_id = $conn->insert_id;

            // Insert schedules if provided manually
            if (!empty($subjects[0])) {
                for ($i = 0; $i < count($subjects); $i++) {
                    if (!empty($subjects[$i])) {
                        $subject = $conn->real_escape_string($subjects[$i]);
                        $day = $conn->real_escape_string($days[$i]);
                        $time = $conn->real_escape_string($times[$i]);
                        $room = $conn->real_escape_string($rooms[$i]);

                        $sched_sql = "INSERT INTO schedules (subject, day, time, room, professor_id, schedule_file)
                                      VALUES ('$subject', '$day', '$time', '$room', $professor_id, " . 
                                      ($schedule_file_path ? "'$schedule_file_path'" : "NULL") . ")";

                        if (!$conn->query($sched_sql)) {
                            echo "Schedule insert error: " . $conn->error;
                            exit();
                        }
                    }
                }
            } elseif ($schedule_file_path) {
                // If only file is uploaded without manual entry, create a single schedule record
                $sched_sql = "INSERT INTO schedules (subject, day, time, room, professor_id, schedule_file)
                              VALUES ('See attached file', '', '', '', $professor_id, '$schedule_file_path')";
                $conn->query($sched_sql);
            }

            // Redirect to admin page on success
            header("Location: admin.php?success=1");
            exit();
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No photo uploaded or upload error.";
}

$conn->close();
?>
