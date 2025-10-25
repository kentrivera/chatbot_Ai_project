<?php
/**
 * Database Setup Script for Chatbot System
 * This script will create the database and all necessary tables
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbot";

// Create connection without database selection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully to MySQL server<br>";

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);

// Drop existing tables if they exist (optional - uncomment if you want fresh install)
// $conn->query("DROP TABLE IF EXISTS schedules");
// $conn->query("DROP TABLE IF EXISTS professors");
// $conn->query("DROP TABLE IF EXISTS users");
// echo "Existing tables dropped<br>";

// Create professors table
$sql_professors = "CREATE TABLE IF NOT EXISTS `professors` (
  `professor_id` int(11) NOT NULL AUTO_INCREMENT,
  `professor_name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `plantilla_title` varchar(100) DEFAULT NULL,
  `years_in_service` int(11) DEFAULT NULL,
  `exam_administered` varchar(255) DEFAULT NULL,
  `course_program` varchar(100) DEFAULT NULL,
  `highest_educ_attainment` varchar(150) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `academic_distinctions` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`professor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_professors) === TRUE) {
    echo "Table 'professors' created successfully or already exists<br>";
} else {
    echo "Error creating professors table: " . $conn->error . "<br>";
}

// Create schedules table
$sql_schedules = "CREATE TABLE IF NOT EXISTS `schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `professor_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `day` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `room` varchar(50) NOT NULL,
  `professor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_schedules) === TRUE) {
    echo "Table 'schedules' created successfully or already exists<br>";
} else {
    echo "Error creating schedules table: " . $conn->error . "<br>";
}

// Create users table
$sql_users = "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_users) === TRUE) {
    echo "Table 'users' created successfully or already exists<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Check if default admin exists
$check_admin = $conn->query("SELECT * FROM users WHERE username = 'admin'");
if ($check_admin->num_rows == 0) {
    // Create default admin user (password: admin123)
    $default_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql_admin = "INSERT INTO users (username, password, role) VALUES ('admin', '$default_password', 'admin')";
    if ($conn->query($sql_admin) === TRUE) {
        echo "<br><strong>Default admin user created successfully!</strong><br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Error creating admin user: " . $conn->error . "<br>";
    }
} else {
    echo "<br>Admin user already exists<br>";
}

// Check if there are any professors
$check_professors = $conn->query("SELECT COUNT(*) as count FROM professors");
$prof_count = $check_professors->fetch_assoc()['count'];

if ($prof_count == 0) {
    echo "<br><strong>No professors found. Adding sample data...</strong><br>";
    
    // Insert sample professor
    $sample_prof = "INSERT INTO `professors` (`professor_name`, `age`, `sex`, `birthdate`, `civil_status`, `plantilla_title`, `years_in_service`, `exam_administered`, `course_program`, `highest_educ_attainment`, `bio`, `expertise`, `academic_distinctions`, `photo`) 
    VALUES 
    ('Dr. Joel A. Perez', 45, 'Male', '1980-01-15', 'Married', 'Professor III', 20, 'CSE Professional', 'BSIT/BSCS', 'Doctor of Philosophy in Computer Science', 'Dr. Joel A. Perez is a seasoned professor with over 20 years of experience in Computer Science and Information Technology. He has published several research papers on algorithms and data structures.', 'Algorithms, Data Structures, Artificial Intelligence', 'Awarded Best IT Professor in 2019 by CPSU Faculty Association', 'Images/1.webp')";
    
    if ($conn->query($sample_prof) === TRUE) {
        $professor_id = $conn->insert_id;
        echo "Sample professor added successfully<br>";
        
        // Add sample schedule
        $sample_schedule = "INSERT INTO `schedules` (`professor_name`, `subject`, `day`, `time`, `room`, `professor_id`) VALUES 
        ('', 'Data Structures', 'Monday', '8:00 AM - 10:00 AM', 'Room 101', $professor_id),
        ('', 'Algorithms', 'Wednesday', '10:00 AM - 12:00 PM', 'Room 102', $professor_id)";
        
        if ($conn->query($sample_schedule) === TRUE) {
            echo "Sample schedules added successfully<br>";
        }
    }
} else {
    echo "<br>Database already contains $prof_count professor(s)<br>";
}

echo "<br><strong>Database setup completed successfully!</strong><br>";
echo "<br><a href='index.php'>Go to Login Page</a> | <a href='admin.php'>Go to Admin Panel</a>";

$conn->close();
?>
