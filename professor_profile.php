<?php
require_once 'security_config.php';
requireAuth(['professor']);

$conn = getDatabaseConnection();
$user_id = $_SESSION['user_id'];

// Get user information
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get professor profile if exists
$professor_profile = null;
if ($user['professor_id']) {
    $stmt = $conn->prepare("SELECT * FROM professors WHERE professor_id = ?");
    $stmt->bind_param("i", $user['professor_id']);
    $stmt->execute();
    $professor_profile = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$success_message = '';
$error_message = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    
    // Update users table
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        
        // If linked to professor table, update there too
        if ($user['professor_id'] && isset($_POST['professor_name'])) {
            $professor_name = sanitizeInput($_POST['professor_name']);
            $age = intval($_POST['age']);
            $sex = sanitizeInput($_POST['sex']);
            $birthdate = sanitizeInput($_POST['birthdate']);
            $civil_status = sanitizeInput($_POST['civil_status']);
            $plantilla_title = sanitizeInput($_POST['plantilla_title']);
            $years_in_service = intval($_POST['years_in_service']);
            $exam_administered = sanitizeInput($_POST['exam_administered']);
            $course_program = sanitizeInput($_POST['course_program']);
            $highest_educ_attainment = sanitizeInput($_POST['highest_educ_attainment']);
            $bio = sanitizeInput($_POST['bio']);
            $expertise = sanitizeInput($_POST['expertise']);
            $academic = sanitizeInput($_POST['academic']);
            
            $stmt = $conn->prepare("UPDATE professors SET professor_name = ?, age = ?, sex = ?, birthdate = ?, civil_status = ?, plantilla_title = ?, years_in_service = ?, exam_administered = ?, course_program = ?, highest_educ_attainment = ?, bio = ?, expertise = ?, academic_distinctions = ? WHERE professor_id = ?");
            $stmt->bind_param("sisssissssssi", $professor_name, $age, $sex, $birthdate, $civil_status, $plantilla_title, $years_in_service, $exam_administered, $course_program, $highest_educ_attainment, $bio, $expertise, $academic, $user['professor_id']);
            $stmt->execute();
            $stmt->close();
            
            // Reload professor profile
            $stmt = $conn->prepare("SELECT * FROM professors WHERE professor_id = ?");
            $stmt->bind_param("i", $user['professor_id']);
            $stmt->execute();
            $professor_profile = $stmt->get_result()->fetch_assoc();
            $stmt->close();
        }
        
        $success_message = 'Profile updated successfully!';
    } else {
        $error_message = 'Failed to update profile. Please try again.';
    }
    $stmt->close();
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password === $confirm_password) {
        // Verify current password
        if (password_verify($current_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($stmt->execute()) {
                $success_message = 'Password changed successfully!';
            } else {
                $error_message = 'Failed to change password. Please try again.';
            }
            $stmt->close();
        } else {
            $error_message = 'Current password is incorrect.';
        }
    } else {
        $error_message = 'New passwords do not match.';
    }
}

closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Professor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stat-card {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">

    <!-- Top Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-lg z-50 border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="professor_dashboard.php" class="text-gray-600 hover:text-purple-600 transition">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center gap-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg p-2">
                            <i class="fas fa-user-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">My Profile</h1>
                            <p class="text-xs text-gray-500">Professor Portal</p>
                        </div>
                    </div>
                </div>
                <button onclick="window.location.href='professor_dashboard.php'" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300">
                    <i class="fas fa-home text-sm"></i>
                    <span class="hidden sm:inline text-sm">Dashboard</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            <?php if ($success_message): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg stat-card">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <span><?php echo $success_message; ?></span>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg stat-card">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                    <span><?php echo $error_message; ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Profile Form -->
            <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 stat-card mb-6">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-6 flex items-center gap-2">
                    <i class="fas fa-user-edit"></i>
                    Personal Information
                </h2>
                
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-500 mr-2"></i>First Name *
                            </label>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-500 mr-2"></i>Last Name *
                            </label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-purple-500 mr-2"></i>Email Address
                            </label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <?php if ($professor_profile): ?>
                    <hr class="my-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Professional Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name (for display)</label>
                            <input type="text" name="professor_name" value="<?php echo htmlspecialchars($professor_profile['professor_name'] ?? ''); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Age</label>
                            <input type="number" name="age" value="<?php echo htmlspecialchars($professor_profile['age'] ?? ''); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sex</label>
                            <select name="sex" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                <option value="Male" <?php echo ($professor_profile['sex'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($professor_profile['sex'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Birthdate</label>
                            <input type="date" name="birthdate" value="<?php echo htmlspecialchars($professor_profile['birthdate'] ?? ''); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Civil Status</label>
                            <select name="civil_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                <option value="Single" <?php echo ($professor_profile['civil_status'] ?? '') == 'Single' ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo ($professor_profile['civil_status'] ?? '') == 'Married' ? 'selected' : ''; ?>>Married</option>
                                <option value="Widowed" <?php echo ($professor_profile['civil_status'] ?? '') == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                                <option value="Separated" <?php echo ($professor_profile['civil_status'] ?? '') == 'Separated' ? 'selected' : ''; ?>>Separated</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Position/Title</label>
                            <input type="text" name="plantilla_title" value="<?php echo htmlspecialchars($professor_profile['plantilla_title'] ?? ''); ?>" 
                                   placeholder="e.g., Professor III"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Years in Service</label>
                            <input type="number" name="years_in_service" value="<?php echo htmlspecialchars($professor_profile['years_in_service'] ?? ''); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Exam Administered</label>
                            <input type="text" name="exam_administered" value="<?php echo htmlspecialchars($professor_profile['exam_administered'] ?? ''); ?>" 
                                   placeholder="e.g., CSE Professional"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Course/Program</label>
                            <input type="text" name="course_program" value="<?php echo htmlspecialchars($professor_profile['course_program'] ?? ''); ?>" 
                                   placeholder="e.g., BSIT/BSCS"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Highest Educational Attainment</label>
                            <input type="text" name="highest_educ_attainment" value="<?php echo htmlspecialchars($professor_profile['highest_educ_attainment'] ?? ''); ?>" 
                                   placeholder="e.g., Doctor of Philosophy in Computer Science"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Biography</label>
                            <textarea name="bio" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition"><?php echo htmlspecialchars($professor_profile['bio'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Expertise</label>
                            <textarea name="expertise" rows="3" placeholder="e.g., Algorithms, Data Structures, AI" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition"><?php echo htmlspecialchars($professor_profile['expertise'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Academic Distinctions</label>
                            <textarea name="academic" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition"><?php echo htmlspecialchars($professor_profile['academic_distinctions'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 text-white py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-xl flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                        <button type="button" onclick="window.location.href='professor_dashboard.php'" class="px-8 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-semibold transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 stat-card">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent mb-6 flex items-center gap-2">
                    <i class="fas fa-lock"></i>
                    Change Password
                </h2>
                
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="change_password" value="1">
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-key text-gray-500 mr-2"></i>Current Password *
                        </label>
                        <input type="password" name="current_password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-blue-500 mr-2"></i>New Password *
                        </label>
                        <input type="password" name="new_password" required minlength="6"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-purple-500 mr-2"></i>Confirm New Password *
                        </label>
                        <input type="password" name="confirm_password" required minlength="6"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-shield-alt"></i>
                        Change Password
                    </button>
                </form>
            </div>

        </div>
    </main>

    <script>
        <?php if ($success_message): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $success_message; ?>',
            confirmButtonColor: '#8b5cf6',
            timer: 3000
        });
        <?php endif; ?>

        <?php if ($error_message): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $error_message; ?>',
            confirmButtonColor: '#ef4444'
        });
        <?php endif; ?>
    </script>

</body>
</html>
