<?php
$conn = new mysqli("localhost", "root", "", "chatbot");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $name = $conn->real_escape_string($_POST['name']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $expertise = $conn->real_escape_string($_POST['expertise']);
    $academic = $conn->real_escape_string($_POST['academic']);
    $age = $conn->real_escape_string($_POST['age']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $civil_status = $conn->real_escape_string($_POST['civil_status']);
    $plantilla_title = $conn->real_escape_string($_POST['plantilla_title']);
    $years_in_service = $conn->real_escape_string($_POST['years_in_service']);
    $exam_administered = $conn->real_escape_string($_POST['exam_administered']);
    $course_program = $conn->real_escape_string($_POST['course_program']);
    $highest_educ_attainment = $conn->real_escape_string($_POST['highest_educ_attainment']);

    // Handle optional photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $target_dir = "Images/";
        $filename = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_sql = ", photo = '$target_file'";
        } else {
            echo "Error uploading photo.";
            exit();
        }
    } else {
        $photo_sql = "";
    }

    // Update the professor's information
    $sql = "UPDATE professors SET
                professor_name = '$name',
                bio = '$bio',
                expertise = '$expertise',
                academic_distinctions = '$academic',
                age = '$age',
                sex = '$sex',
                birthdate = '$birthdate',
                civil_status = '$civil_status',
                plantilla_title = '$plantilla_title',
                years_in_service = '$years_in_service',
                exam_administered = '$exam_administered',
                course_program = '$course_program',
                highest_educ_attainment = '$highest_educ_attainment'
                $photo_sql
            WHERE professor_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?success=2");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    // Fetch existing data to populate the form
    $result = $conn->query("SELECT * FROM professors WHERE professor_id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Professor not found.";
        exit();
    }
}
$conn->close();
?>

<!-- Display Update Form -->
<form action="" method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($row['professor_name']) ?>" required><br>

    <label>Bio:</label>
    <textarea name="bio" required><?= htmlspecialchars($row['bio']) ?></textarea><br>

    <label>Expertise:</label>
    <input type="text" name="expertise" value="<?= htmlspecialchars($row['expertise']) ?>" required><br>

    <label>Academic Distinctions:</label>
    <input type="text" name="academic" value="<?= htmlspecialchars($row['academic_distinctions']) ?>" required><br>

    <label>Age:</label>
    <input type="number" name="age" value="<?= htmlspecialchars($row['age']) ?>" required><br>

    <label>Sex:</label>
    <select name="sex" required>
        <option value="Male" <?= $row['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $row['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
    </select><br>

    <label>Birthdate:</label>
    <input type="date" name="birthdate" value="<?= htmlspecialchars($row['birthdate']) ?>" required><br>

    <label>Civil Status:</label>
    <input type="text" name="civil_status" value="<?= htmlspecialchars($row['civil_status']) ?>" required><br>

    <label>Plantilla Title:</label>
    <input type="text" name="plantilla_title" value="<?= htmlspecialchars($row['plantilla_title']) ?>" required><br>

    <label>Years in Service:</label>
    <input type="number" name="years_in_service" value="<?= htmlspecialchars($row['years_in_service']) ?>" required><br>

    <label>Exam Administered:</label>
    <input type="text" name="exam_administered" value="<?= htmlspecialchars($row['exam_administered']) ?>" required><br>

    <label>Course/Program:</label>
    <input type="text" name="course_program" value="<?= htmlspecialchars($row['course_program']) ?>" required><br>

    <label>Highest Educational Attainment:</label>
    <input type="text" name="highest_educ_attainment" value="<?= htmlspecialchars($row['highest_educ_attainment']) ?>" required><br>

    <label>Change Photo (optional):</label>
    <input type="file" name="photo"><br>

    <button type="submit">Update Professor</button>
</form>
