<?php
session_start();
$conn = new mysqli("localhost", "root", "", "chatbot");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get account information
$username = $conn->real_escape_string($_POST['username']);
$password = $_POST['password'];
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;

// Get professor information
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

// Check if username already exists
$check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
if ($check_stmt->get_result()->num_rows > 0) {
    $check_stmt->close();
    $conn->close();
    header("Location: professors.php?error=username_exists");
    exit();
}
$check_stmt->close();

// Split name into first and last name
$name_parts = explode(' ', $name, 2);
$first_name = $name_parts[0];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

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
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert into professors table first
            $sql = "INSERT INTO professors (
                professor_name, age, sex, birthdate, civil_status, plantilla_title,
                years_in_service, exam_administered, course_program, highest_educ_attainment, 
                bio, expertise, academic_distinctions, photo
            ) VALUES (
                '$name', '$age', '$sex', '$birthdate', '$civil_status', '$plantilla_title',
                '$years_in_service', '$exam_administered', '$course_program', '$highest_educ_attainment', 
                '$bio', '$expertise', '$academic','$target_file'
            )";

            if (!$conn->query($sql)) {
                throw new Exception("Error inserting professor: " . $conn->error);
            }
            
            $professor_id = $conn->insert_id;

            // Hash password and insert into users table
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $user_stmt = $conn->prepare("INSERT INTO users (username, password, role, first_name, last_name, email, professor_id, status) VALUES (?, ?, 'professor', ?, ?, ?, ?, 'active')");
            $user_stmt->bind_param("sssssi", $username, $hashed_password, $first_name, $last_name, $email, $professor_id);
            
            if (!$user_stmt->execute()) {
                throw new Exception("Error creating user account: " . $user_stmt->error);
            }
            $user_stmt->close();

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
                            throw new Exception("Schedule insert error: " . $conn->error);
                        }
                    }
                }
            } elseif ($schedule_file_path) {
                // If only file is uploaded without manual entry, create a single schedule record
                $sched_sql = "INSERT INTO schedules (subject, day, time, room, professor_id, schedule_file)
                              VALUES ('See attached file', '', '', '', $professor_id, '$schedule_file_path')";
                if (!$conn->query($sched_sql)) {
                    throw new Exception("Schedule file insert error: " . $conn->error);
                }
            }

            // Commit transaction
            $conn->commit();
            
            // Redirect to professors page on success
            header("Location: professors.php?success=1");
            exit();
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            
            // Delete uploaded photo if exists
            if (file_exists($target_file)) {
                unlink($target_file);
            }
            
            // Delete schedule file if exists
            if ($schedule_file_path && file_exists($schedule_file_path)) {
                unlink($schedule_file_path);
            }
            
            $conn->close();
            header("Location: professors.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    } else {
        $conn->close();
        header("Location: professors.php?error=upload_failed");
        exit();
    }
} else {
    $conn->close();
    header("Location: professors.php?error=no_photo");
    exit();
}

$conn->close();
?>
