<?php
session_start();
// Check if user is logged in and is admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professors Management - Chatbot System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php 
    $page_title = "Professors Management";
    $page_subtitle = "Manage Professors & Schedules";
    include 'includes/styles.php'; 
    ?>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    <?php include 'includes/topnav.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main id="mainContent" class="transition-all duration-300 pt-20 lg:pt-24 p-4 sm:p-6 lg:p-8">
        
        <!-- Professors List Section -->
        <section>
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent flex items-center gap-2">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            Professors List
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Manage all professors and their information</p>
                    </div>
                    <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-4 sm:px-6 py-3 rounded-lg flex items-center gap-2 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105">
                        <i class="fa-solid fa-plus"></i>
                        Add Professor
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-medium text-gray-700 uppercase tracking-wider">Photo</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-medium text-gray-700 uppercase tracking-wider">Position</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-medium text-gray-700 uppercase tracking-wider hidden md:table-cell">Expertise</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-medium text-gray-700 uppercase tracking-wider hidden sm:table-cell">Schedules</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $conn = new mysqli("localhost", "root", "", "chatbot");
                            if (!$conn->connect_error) {
                                $result = $conn->query("SELECT * FROM professors ORDER BY professor_name");
                                if ($result && $result->num_rows > 0) {
                                    $count = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        $count++;
                                        $delay = $count * 0.05;
                                        
                                        // Get schedule count for this professor
                                        $sched_count = $conn->query("SELECT COUNT(*) as count FROM schedules WHERE professor_id = " . $row['professor_id'])->fetch_assoc()['count'];
                                        
                                        echo "<tr class='hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200 stat-card' style='animation-delay: {$delay}s'>";
                                                                                echo "<td class='px-4 sm:px-6 py-3 sm:py-4'><img src='" . htmlspecialchars($row['photo']) . "' class='w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover shadow-md border-2 border-purple-200' alt='Professor photo'></td>";
                                                                                echo "<td class='px-4 sm:px-6 py-3 sm:py-4'><div class='text-xs sm:text-sm font-semibold text-gray-900'>" . htmlspecialchars($row['professor_name']) . "</div></td>";
                                                                                echo "<td class='px-4 sm:px-6 py-3 sm:py-4'><span class='inline-block px-2 sm:px-3 py-1 text-[10px] sm:text-xs font-medium rounded-full bg-blue-100 text-blue-800 whitespace-nowrap'>" . htmlspecialchars($row['plantilla_title']) . "</span></td>";
                                                                                echo "<td class='px-4 sm:px-6 py-3 sm:py-4 hidden md:table-cell'><div class='text-xs sm:text-sm text-gray-600 truncate max-w-[10rem] lg:max-w-xs'>" . htmlspecialchars($row['expertise']) . "</div></td>";
                                                                                echo "<td class='px-4 sm:px-6 py-3 sm:py-4 hidden sm:table-cell'><span class='inline-flex items-center gap-1 px-2 sm:px-3 py-1 text-[10px] sm:text-xs font-semibold rounded-full bg-purple-100 text-purple-800 whitespace-nowrap'><i class='fa-solid fa-calendar-days'></i> {$sched_count} Classes</span></td>";
                                        echo "<td class='px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium'>
                                                <div class='flex flex-wrap items-center gap-2'>
                                                    <button aria-label='View details' onclick='viewProfessor({$row['professor_id']})' class='text-blue-600 hover:text-blue-900 hover:scale-110 transition-transform' title='View Details'><i class='fa-solid fa-eye text-base sm:text-lg'></i></button>
                                                    <button aria-label='View schedules' onclick='viewSchedules({$row['professor_id']}, \"" . htmlspecialchars($row['professor_name']) . "\")' class='text-green-600 hover:text-green-900 hover:scale-110 transition-transform' title='View Schedules'><i class='fa-solid fa-calendar-check text-base sm:text-lg'></i></button>
                                                </div>
                                                </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='px-4 sm:px-6 py-10 sm:py-12 text-center text-gray-500'><i class='fa-solid fa-user-slash text-3xl sm:text-4xl mb-2 block'></i>No professors found</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <!-- Add Professor Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center z-10">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Add New Professor</h3>
                    <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                
                <form action="prof_add.php" method="POST" enctype="multipart/form-data" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Basic Information -->
                        <div class="col-span-2">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-circle-user text-blue-500"></i>
                                Basic Information
                            </h4>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Photo *</label>
                            <input type="file" name="photo" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age *</label>
                            <input type="number" name="age" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sex *</label>
                            <select name="sex" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate *</label>
                            <input type="date" name="birthdate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status *</label>
                            <select name="civil_status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                <option value="">Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Separated">Separated</option>
                            </select>
                        </div>

                        <!-- Professional Information -->
                        <div class="col-span-2 mt-4">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-briefcase text-purple-500"></i>
                                Professional Information
                            </h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position/Title *</label>
                            <input type="text" name="plantilla_title" placeholder="e.g., Professor III" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Years in Service *</label>
                            <input type="number" name="years_in_service" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Exam Administered</label>
                            <input type="text" name="exam_administered" placeholder="e.g., CSE Professional" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Course/Program *</label>
                            <input type="text" name="course_program" placeholder="e.g., BSIT/BSCS" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Highest Educational Attainment *</label>
                            <input type="text" name="highest_educ_attainment" placeholder="e.g., Doctor of Philosophy in Computer Science" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Biography *</label>
                            <textarea name="bio" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition"></textarea>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expertise *</label>
                            <textarea name="expertise" rows="2" placeholder="e.g., Algorithms, Data Structures, AI" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition"></textarea>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Academic Distinctions *</label>
                            <textarea name="academic" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition"></textarea>
                        </div>

                        <!-- Schedule Information -->
                        <div class="col-span-2 mt-4">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days text-green-500"></i>
                                Schedule
                            </h4>
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                                <p class="text-sm text-blue-700">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Upload a schedule file (PDF, Word, or Image) or fill in manually below
                                </p>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Schedule File (Optional)</label>
                            <input type="file" name="schedule_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                            <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, Word, Images (JPG, PNG)</p>
                        </div>

                        <div class="col-span-2">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-sm font-medium text-gray-700">Manual Schedule Entry</label>
                                <button type="button" onclick="addScheduleField()" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-3 py-1 rounded-lg text-sm flex items-center gap-1 shadow-sm hover:shadow-md transition-all duration-200">
                                    <i class="fa-solid fa-plus"></i> Add Schedule
                                </button>
                            </div>
                            <div id="scheduleContainer" class="space-y-3">
                                <div class="schedule-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="subject[]" placeholder="Subject" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                        <input type="text" name="day[]" placeholder="Day" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                        <input type="text" name="time[]" placeholder="Time" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                        <input type="text" name="room[]" placeholder="Room" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-6 border-t">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 text-white py-3 rounded-lg font-semibold flex items-center justify-center gap-2 transition-all duration-300 shadow-md hover:shadow-xl">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save Professor
                        </button>
                        <button type="button" onclick="closeAddModal()" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Schedule Modal -->
    <div id="createScheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[70] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full">
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center z-10">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">Create Schedule</h3>
                    <button onclick="closeCreateSchedule()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <form action="schedule_add.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    <input type="hidden" name="professor_id" id="createSchedProfessorId">
                    <div class="bg-gray-50 border rounded-lg p-3 text-sm text-gray-700">
                        <i class="fa-solid fa-chalkboard-user text-green-600"></i>
                        <span class="ml-2">Professor: </span>
                        <span id="createSchedProfessorName" class="font-semibold"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject *</label>
                        <input type="text" name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="e.g., Data Structures">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Day *</label>
                            <select name="day" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time *</label>
                            <input type="text" name="time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="e.g., 8:00 AM - 10:00 AM">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room *</label>
                        <input type="text" name="room" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="e.g., Room 101">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Attach File (Optional)</label>
                        <input type="file" name="schedule_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                        <p class="text-xs text-gray-500 mt-1">Accepted: PDF, Word, Images (JPG, PNG, GIF, WEBP)</p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white py-3 rounded-lg font-semibold flex items-center justify-center gap-2 transition-all duration-300 shadow-md hover:shadow-xl">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save Schedule
                        </button>
                        <button type="button" onclick="closeCreateSchedule()" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Professor Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center z-10">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Professor Details</h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <div id="viewModalContent" class="p-6"></div>
            </div>
        </div>
    </div>

    <!-- Edit Professor Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center z-10">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Edit Professor</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <div id="editModalContent" class="p-6"></div>
            </div>
        </div>
    </div>

    <!-- View Schedules Modal -->
    <div id="schedulesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center z-10">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">Professor Schedules</h3>
                    <button onclick="closeSchedulesModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <div id="schedulesModalContent" class="p-6"></div>
            </div>
        </div>
    </div>

    <script>
        // Basic HTML escape to prevent XSS in injected modal content
        const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]));

        // Add Professor Modal
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        // Add Schedule Field
        function addScheduleField() {
            const container = document.getElementById('scheduleContainer');
            const div = document.createElement('div');
            div.className = 'schedule-item bg-gray-50 p-4 rounded-lg border border-gray-200 relative';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="subject[]" placeholder="Subject" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                    <input type="text" name="day[]" placeholder="Day" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                    <input type="text" name="time[]" placeholder="Time" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                    <input type="text" name="room[]" placeholder="Room" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                </div>
            `;
            container.appendChild(div);
        }

        // View Professor
        function viewProfessor(id) {
            fetch(`get_professor.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    const content = `
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="col-span-1 text-center">
                                <img src="${esc(data.photo)}" class="w-40 h-40 sm:w-48 sm:h-48 rounded-full mx-auto object-cover shadow-lg border-4 border-purple-200 mb-4" alt="Professor photo">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">${esc(data.professor_name)}</h3>
                                <p class="text-purple-600 font-medium mt-1">${esc(data.plantilla_title || 'N/A')}</p>
                                <div class="mt-4 space-y-2">
                                    <div class="bg-blue-50 rounded-lg p-3">
                                        <p class="text-xs text-gray-600">Years in Service</p>
                                        <p class="text-lg font-bold text-blue-600">${esc(data.years_in_service || 'N/A')}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <div class="space-y-4">
                                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg">
                                        <h4 class="text-sm font-semibold text-gray-700 uppercase flex items-center gap-2 mb-2">
                                            <i class="fa-solid fa-user text-blue-500"></i>
                                            Personal Information
                                        </h4>
                                        <div class="grid grid-cols-2 gap-3">
                                            <p class="text-sm"><span class="font-medium text-gray-700">Age:</span> <span class="text-gray-600">${esc(data.age || 'N/A')}</span></p>
                                            <p class="text-sm"><span class="font-medium text-gray-700">Sex:</span> <span class="text-gray-600">${esc(data.sex || 'N/A')}</span></p>
                                            <p class="text-sm"><span class="font-medium text-gray-700">Birthdate:</span> <span class="text-gray-600">${esc(data.birthdate || 'N/A')}</span></p>
                                            <p class="text-sm"><span class="font-medium text-gray-700">Civil Status:</span> <span class="text-gray-600">${esc(data.civil_status || 'N/A')}</span></p>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg">
                                        <h4 class="text-sm font-semibold text-gray-700 uppercase flex items-center gap-2 mb-2">
                                            <i class="fa-solid fa-briefcase text-purple-500"></i>
                                            Professional Information
                                        </h4>
                                        <div class="space-y-2">
                                            <p class="text-sm"><span class="font-medium text-gray-700">Education:</span> <span class="text-gray-600">${esc(data.highest_educ_attainment || 'N/A')}</span></p>
                                            <p class="text-sm"><span class="font-medium text-gray-700">Course/Program:</span> <span class="text-gray-600">${esc(data.course_program || 'N/A')}</span></p>
                                        </div>
                                    </div>
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-semibold text-gray-700 uppercase flex items-center gap-2 mb-2">
                                            <i class="fa-solid fa-book-open text-green-500"></i>
                                            Biography
                                        </h4>
                                        <p class="text-gray-700 text-sm leading-relaxed">${esc(data.bio || 'N/A')}</p>
                                    </div>
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-semibold text-gray-700 uppercase flex items-center gap-2 mb-2">
                                            <i class="fa-solid fa-lightbulb text-yellow-500"></i>
                                            Expertise
                                        </h4>
                                        <p class="text-gray-700 text-sm leading-relaxed">${esc(data.expertise || 'N/A')}</p>
                                    </div>
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-semibold text-gray-700 uppercase flex items-center gap-2 mb-2">
                                            <i class="fa-solid fa-award text-pink-500"></i>
                                            Academic Distinctions
                                        </h4>
                                        <p class="text-gray-700 text-sm leading-relaxed">${esc(data.academic_distinctions || 'N/A')}</p>
                                    </div>
                                    <div class="border-t pt-4">
                                        <div class="flex flex-wrap gap-3 justify-end">
                                            <button onclick="editProfessor(${data.professor_id})" class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold flex items-center gap-2 transition-colors" title="Edit Professor">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                Edit
                                            </button>
                                            <button onclick="deleteProfessor(${data.professor_id})" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold flex items-center gap-2 transition-colors" title="Delete Professor">
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('viewModalContent').innerHTML = content;
                    document.getElementById('viewModal').classList.remove('hidden');
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // Edit Professor
        function editProfessor(id) {
            fetch(`get_professor.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    const form = `
                        <form action="prof_update.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="${data.professor_id}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" value="${data.professor_name}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                                    <input type="number" name="age" value="${data.age || ''}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                                    <select name="sex" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                        <option value="Male" ${data.sex === 'Male' ? 'selected' : ''}>Male</option>
                                        <option value="Female" ${data.sex === 'Female' ? 'selected' : ''}>Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
                                    <input type="date" name="birthdate" value="${data.birthdate || ''}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status</label>
                                    <input type="text" name="civil_status" value="${data.civil_status || ''}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                    <input type="text" name="plantilla_title" value="${data.plantilla_title || ''}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Years in Service</label>
                                    <input type="number" name="years_in_service" value="${data.years_in_service || ''}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Course/Program</label>
                                    <input type="text" name="course_program" value="${data.course_program || ''}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Highest Educational Attainment</label>
                                    <input type="text" name="highest_educ_attainment" value="${data.highest_educ_attainment || ''}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                                    <textarea name="bio" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">${data.bio || ''}</textarea>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Expertise</label>
                                    <textarea name="expertise" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">${data.expertise || ''}</textarea>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Distinctions</label>
                                    <textarea name="academic" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition">${data.academic_distinctions || ''}</textarea>
                                </div>
                            </div>
                            <div class="flex gap-3 mt-6 pt-6 border-t">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 text-white py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-xl">
                                    <i class="fa-solid fa-floppy-disk"></i> Update Professor
                                </button>
                                <button type="button" onclick="closeEditModal()" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    `;
                    document.getElementById('editModalContent').innerHTML = form;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // View Schedules
        function viewSchedules(id, name) {
            fetch(`get_professor.php?id=${id}&schedules=1`)
                .then(res => res.json())
                .then(data => {
                    const filesRaw = (data.schedules || []).map(s => s.schedule_file).filter(Boolean);
                    const attachFiles = Array.from(new Set(filesRaw));
                    let attachBlock = '';
                    if (attachFiles.length) {
                        const galleryId = 'attachStrip_' + Math.random().toString(36).slice(2,9);
                        const controls = attachFiles.length > 1 ? `
                            <div class="inline-flex gap-2">
                                <button type="button" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg shadow-sm" onclick="(function(){const el=document.getElementById('${galleryId}'); if(el){ el.scrollBy({left:-240, behavior:'smooth'}); }})()"><i class="fa-solid fa-chevron-left"></i></button>
                                <button type="button" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg shadow-sm" onclick="(function(){const el=document.getElementById('${galleryId}'); if(el){ el.scrollBy({left:240, behavior:'smooth'}); }})()"><i class="fa-solid fa-chevron-right"></i></button>
                            </div>
                        ` : '';
                        attachBlock = `
                            <div class="mt-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="inline-flex items-center gap-2 text-white/90">
                                        <i class="fa-solid fa-paperclip"></i>
                                        <span>Attachments (${attachFiles.length})</span>
                                    </div>
                                    ${controls}
                                </div>
                                <div id="${galleryId}" class="flex gap-3 overflow-x-auto pb-1 snap-x">
                                    ${attachFiles.map(f => { 
                                        const ext = String(f).split('.').pop().toLowerCase();
                                        const isImg = ['jpg','jpeg','png','gif','webp'].includes(ext);
                                        let icon = 'fa-file';
                                        if (ext === 'pdf') icon = 'fa-file-pdf';
                                        else if (['doc','docx'].includes(ext)) icon = 'fa-file-word';
                                        else if (isImg) icon = 'fa-file-image';
                                        return isImg 
                                            ? `<a href="${esc(f)}" target="_blank" rel="noopener" class="snap-start shrink-0"><img src="${esc(f)}" alt="Attachment" class="w-28 h-20 rounded-md object-cover ring-1 ring-white/60 shadow" /></a>`
                                            : `<a href="${esc(f)}" target="_blank" rel="noopener" class="snap-start shrink-0 w-28 h-20 rounded-md bg-white/15 hover:bg-white/25 flex items-center justify-center gap-2 text-white shadow">
                                                 <i class="fa-solid ${icon} text-xl"></i>
                                                 <span class="text-xs uppercase">${ext}</span>
                                               </a>`;
                                    }).join('')}
                                </div>
                            </div>
                        `;
                    }
                    let content = `
                        <div class="mb-4 p-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg text-white">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h4 class="text-xl font-bold">${name}</h4>
                                    <p class="text-sm opacity-90">Teaching Schedule</p>
                                </div>
                                <button onclick="openCreateSchedule(${id}, '${esc(name)}')" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg font-medium shadow-sm">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                    <span class="hidden sm:inline">Create Schedule</span>
                                </button>
                            </div>
                            ${attachBlock}
                        </div>
                    `;
                    
                    if (data.schedules && data.schedules.length > 0) {
                        content += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                        data.schedules.forEach((sched, index) => {
                            const delay = index * 0.1;
                            const subject = sched.subject || sched.subject_code || 'N/A';
                            const description = sched.subject_description ? `<p class=\"text-xs text-gray-600\">${esc(sched.subject_description)}</p>` : '';
                            const badge = sched.year_level ? `<span class=\"inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800\">${esc(sched.year_level)}</span>` : '';
                            const dayVal = sched.day || sched.days || 'N/A';
                            const timeVal = sched.time || 'N/A';
                            const roomVal = sched.room || 'N/A';
                            // Attachment link is shown under the modal title; omit per-card file link
                            content += `
                                <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all duration-300 stat-card" style="animation-delay: ${delay}s">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-bold text-gray-800 truncate">${esc(subject)}</h5>
                                            ${description}
                                        </div>
                                        ${badge}
                                    </div>
                                        <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fa-solid fa-calendar-day text-pink-500 w-4"></i>
                                            <span class="truncate">${esc(dayVal)}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fa-solid fa-clock text-purple-500 w-4"></i>
                                            <span class="truncate">${esc(timeVal)}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <i class="fa-solid fa-door-open text-blue-500 w-4"></i>
                                            <span class="font-semibold truncate">${esc(roomVal)}</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            `;
                        });
                        content += '</div>';
                    } else {
                        content += `
                            <div class="text-center py-12 text-gray-500">
                                <i class="fa-solid fa-calendar-xmark text-4xl mb-3 block"></i>
                                <p class="text-lg">No schedules assigned yet</p>
                            </div>
                        `;
                    }
                    
                    document.getElementById('schedulesModalContent').innerHTML = content;
                    document.getElementById('schedulesModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load schedules',
                        confirmButtonColor: '#8b5cf6'
                    });
                });
        }

        function closeSchedulesModal() {
            document.getElementById('schedulesModal').classList.add('hidden');
        }

        // Delete Professor
        function deleteProfessor(id) {
            Swal.fire({
                title: 'Delete Professor?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'prof_delete.php';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_id';
                    input.value = id;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Create Schedule Modal
        function openCreateSchedule(id, name) {
            document.getElementById('createSchedProfessorName').textContent = name;
            document.getElementById('createSchedProfessorId').value = id;
            document.getElementById('createScheduleModal').classList.remove('hidden');
        }

        function closeCreateSchedule() {
            document.getElementById('createScheduleModal').classList.add('hidden');
        }

        // Show success message if redirected with success parameter
        <?php if (isset($_GET['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $_GET['success'] === '1' ? 'Professor added successfully!' : ($_GET['success'] === 'updated' ? 'Professor updated successfully!' : 'Operation completed successfully!'); ?>',
            confirmButtonColor: '#8b5cf6',
            timer: 3000
        });
        window.history.replaceState({}, document.title, window.location.pathname);
        <?php endif; ?>

        <?php if (isset($_GET['sched_success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Schedule Created',
            text: 'New schedule has been added.',
            confirmButtonColor: '#8b5cf6',
            timer: 2500
        });
        window.history.replaceState({}, document.title, window.location.pathname);
        <?php endif; ?>
    </script>

    <?php 
    if ($conn && !$conn->connect_error) {
        $conn->close();
    }
    include 'includes/scripts.php'; 
    ?>

</body>
</html>
