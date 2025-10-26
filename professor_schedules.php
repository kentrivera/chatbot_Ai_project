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

$success_message = '';
$error_message = '';

// Handle Add Schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_schedule']) && $user['professor_id']) {
    $subject = sanitizeInput($_POST['subject']);
    $day = sanitizeInput($_POST['day']);
    $time = sanitizeInput($_POST['time']);
    $room = sanitizeInput($_POST['room']);
    $professor_id = $user['professor_id'];
    
    $stmt = $conn->prepare("INSERT INTO schedules (professor_id, subject, day, time, room) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $professor_id, $subject, $day, $time, $room);
    
    if ($stmt->execute()) {
        $success_message = 'Schedule added successfully!';
    } else {
        $error_message = 'Failed to add schedule. Please try again.';
    }
    $stmt->close();
}

// Handle Update Schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_schedule']) && $user['professor_id']) {
    $schedule_id = intval($_POST['schedule_id']);
    $subject = sanitizeInput($_POST['subject']);
    $day = sanitizeInput($_POST['day']);
    $time = sanitizeInput($_POST['time']);
    $room = sanitizeInput($_POST['room']);
    
    // Verify schedule belongs to this professor
    $stmt = $conn->prepare("SELECT professor_id FROM schedules WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();
    
    if ($schedule && $schedule['professor_id'] == $user['professor_id']) {
        $stmt = $conn->prepare("UPDATE schedules SET subject = ?, day = ?, time = ?, room = ? WHERE schedule_id = ?");
        $stmt->bind_param("ssssi", $subject, $day, $time, $room, $schedule_id);
        
        if ($stmt->execute()) {
            $success_message = 'Schedule updated successfully!';
        } else {
            $error_message = 'Failed to update schedule. Please try again.';
        }
        $stmt->close();
    } else {
        $error_message = 'Unauthorized access to this schedule.';
    }
}

// Handle Delete Schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_schedule']) && $user['professor_id']) {
    $schedule_id = intval($_POST['schedule_id']);
    
    // Verify schedule belongs to this professor
    $stmt = $conn->prepare("SELECT professor_id FROM schedules WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();
    
    if ($schedule && $schedule['professor_id'] == $user['professor_id']) {
        $stmt = $conn->prepare("DELETE FROM schedules WHERE schedule_id = ?");
        $stmt->bind_param("i", $schedule_id);
        
        if ($stmt->execute()) {
            $success_message = 'Schedule deleted successfully!';
        } else {
            $error_message = 'Failed to delete schedule. Please try again.';
        }
        $stmt->close();
    } else {
        $error_message = 'Unauthorized access to this schedule.';
    }
}

// Get all schedules for this professor
$schedules = [];
if ($user['professor_id']) {
    $stmt = $conn->prepare("SELECT * FROM schedules WHERE professor_id = ? ORDER BY 
        CASE day
            WHEN 'Monday' THEN 1
            WHEN 'Tuesday' THEN 2
            WHEN 'Wednesday' THEN 3
            WHEN 'Thursday' THEN 4
            WHEN 'Friday' THEN 5
            WHEN 'Saturday' THEN 6
            WHEN 'Sunday' THEN 7
        END,
        time");
    $stmt->bind_param("i", $user['professor_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    $stmt->close();
}

closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedules - Professor Portal</title>
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
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-2">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">My Schedules</h1>
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
        <div class="max-w-6xl mx-auto">
            
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

            <?php if (!$user['professor_id']): ?>
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-6 mb-6 rounded-lg stat-card">
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-3 text-2xl"></i>
                    <div>
                        <p class="font-semibold">Profile Not Linked</p>
                        <p class="text-sm">Your account is not linked to a professor profile. Please contact the administrator to link your account.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Add Schedule Button -->
            <?php if ($user['professor_id']): ?>
            <div class="mb-6 flex justify-end">
                <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105">
                    <i class="fas fa-plus"></i>
                    Add New Schedule
                </button>
            </div>
            <?php endif; ?>

            <!-- Schedules List -->
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-6 flex items-center gap-2">
                    <i class="fas fa-list"></i>
                    Your Teaching Schedule
                </h2>
                
                <?php if (!empty($schedules)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($schedules as $index => $schedule): ?>
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-5 border-l-4 border-purple-500 stat-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="font-bold text-lg text-gray-800"><?php echo htmlspecialchars($schedule['subject'] ?? $schedule['subject_code'] ?? 'N/A'); ?></h3>
                                    <div class="flex gap-2">
                                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($schedule)); ?>)" 
                                                class="text-blue-600 hover:text-blue-800 hover:scale-110 transition-transform" title="Edit">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        <button onclick="confirmDelete(<?php echo $schedule['schedule_id']; ?>)" 
                                                class="text-red-600 hover:text-red-800 hover:scale-110 transition-transform" title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm text-gray-700">
                                    <p><i class="fas fa-calendar-day text-purple-500 w-5"></i> <?php echo htmlspecialchars($schedule['day'] ?? $schedule['days'] ?? 'N/A'); ?></p>
                                    <p><i class="fas fa-clock text-blue-500 w-5"></i> <?php echo htmlspecialchars($schedule['time'] ?? 'N/A'); ?></p>
                                    <p><i class="fas fa-door-open text-pink-500 w-5"></i> <?php echo htmlspecialchars($schedule['room'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-calendar-xmark text-6xl mb-4 block text-gray-300"></i>
                        <p class="text-xl font-semibold">No schedules yet</p>
                        <p class="text-sm mb-6">Add your first teaching schedule to get started</p>
                        <?php if ($user['professor_id']): ?>
                        <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Add Schedule
                        </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- Add Schedule Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Add New Schedule</h3>
                <button onclick="closeAddModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form method="POST" class="p-6 space-y-4">
                <input type="hidden" name="add_schedule" value="1">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-book text-blue-500 mr-2"></i>Subject *
                    </label>
                    <input type="text" name="subject" required 
                           placeholder="e.g., Data Structures"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-day text-purple-500 mr-2"></i>Day *
                    </label>
                    <select name="day" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        <option value="">Select Day</option>
                        <option>Monday</option>
                        <option>Tuesday</option>
                        <option>Wednesday</option>
                        <option>Thursday</option>
                        <option>Friday</option>
                        <option>Saturday</option>
                        <option>Sunday</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock text-green-500 mr-2"></i>Time *
                    </label>
                    <input type="text" name="time" required 
                           placeholder="e.g., 8:00 AM - 10:00 AM"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-door-open text-pink-500 mr-2"></i>Room *
                    </label>
                    <input type="text" name="room" required 
                           placeholder="e.g., Room 101"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add Schedule
                    </button>
                    <button type="button" onclick="closeAddModal()" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-semibold transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="bg-gradient-to-r from-green-600 to-blue-600 px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Edit Schedule</h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form method="POST" class="p-6 space-y-4" id="editForm">
                <input type="hidden" name="update_schedule" value="1">
                <input type="hidden" name="schedule_id" id="edit_schedule_id">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-book text-blue-500 mr-2"></i>Subject *
                    </label>
                    <input type="text" name="subject" id="edit_subject" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-day text-purple-500 mr-2"></i>Day *
                    </label>
                    <select name="day" id="edit_day" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Select Day</option>
                        <option>Monday</option>
                        <option>Tuesday</option>
                        <option>Wednesday</option>
                        <option>Thursday</option>
                        <option>Friday</option>
                        <option>Saturday</option>
                        <option>Sunday</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock text-green-500 mr-2"></i>Time *
                    </label>
                    <input type="text" name="time" id="edit_time" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-door-open text-pink-500 mr-2"></i>Room *
                    </label>
                    <input type="text" name="room" id="edit_room" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-semibold transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_schedule" value="1">
        <input type="hidden" name="schedule_id" id="delete_schedule_id">
    </form>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(schedule) {
            document.getElementById('edit_schedule_id').value = schedule.schedule_id;
            document.getElementById('edit_subject').value = schedule.subject || schedule.subject_code || '';
            document.getElementById('edit_day').value = schedule.day || schedule.days || '';
            document.getElementById('edit_time').value = schedule.time || '';
            document.getElementById('edit_room').value = schedule.room || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(scheduleId) {
            Swal.fire({
                title: 'Delete Schedule?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_schedule_id').value = scheduleId;
                    document.getElementById('deleteForm').submit();
                }
            });
        }

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
