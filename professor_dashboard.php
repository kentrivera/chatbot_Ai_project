<?php
require_once 'security_config.php';
requireAuth(['professor']);

// Get database connection
$conn = getDatabaseConnection();

// Get professor information
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get linked professor profile if exists
$professor_profile = null;
if ($user['professor_id']) {
    $stmt = $conn->prepare("SELECT * FROM professors WHERE professor_id = ?");
    $stmt->bind_param("i", $user['professor_id']);
    $stmt->execute();
    $professor_profile = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Get schedule count
$schedule_count = 0;
if ($user['professor_id']) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM schedules WHERE professor_id = ?");
    $stmt->bind_param("i", $user['professor_id']);
    $stmt->execute();
    $schedule_count = $stmt->get_result()->fetch_assoc()['count'];
    $stmt->close();
}

// Get upcoming schedules (today and next 7 days)
$upcoming_schedules = [];
if ($user['professor_id']) {
    $stmt = $conn->prepare("SELECT * FROM schedules WHERE professor_id = ? ORDER BY day, time LIMIT 10");
    $stmt->bind_param("i", $user['professor_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $upcoming_schedules[] = $row;
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
    <title>Professor Dashboard - Chatbot System</title>
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
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">

    <!-- Top Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-lg z-50 border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg p-2">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Professor Portal</h1>
                            <p class="text-xs text-gray-500">Chatbot System</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-3 bg-gradient-to-r from-blue-50 to-purple-50 px-4 py-2 rounded-xl border border-purple-100">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                            <?php 
                                $initials = strtoupper(substr($_SESSION['first_name'] ?? $_SESSION['username'], 0, 1) . substr($_SESSION['last_name'] ?? '', 0, 1));
                                echo $initials;
                            ?>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></p>
                            <p class="text-xs text-gray-500">Professor</p>
                        </div>
                    </div>
                    <button onclick="confirmLogout()" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                        <span class="hidden sm:inline text-sm font-medium">Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Welcome Header -->
            <header class="mb-8 stat-card">
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>! 👋
                    </h1>
                    <p class="text-gray-600 mt-1">Manage your profile, schedules, and view your information</p>
                </div>
            </header>

            <!-- Statistics Cards -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Profile Card -->
                <a href="professor_profile.php" class="stat-card card-hover bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white cursor-pointer group no-underline" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">My Profile</p>
                            <h3 class="text-2xl font-bold mt-2"><?php echo $professor_profile ? 'Complete' : 'Incomplete'; ?></h3>
                            <p class="text-xs text-blue-200 mt-1">Click to manage</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-user-circle text-4xl"></i>
                        </div>
                    </div>
                </a>

                <!-- Schedules Card -->
                <a href="professor_schedules.php" class="stat-card card-hover bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white cursor-pointer group no-underline" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">My Schedules</p>
                            <h3 class="text-4xl font-bold mt-2"><?php echo $schedule_count; ?></h3>
                            <p class="text-xs text-purple-200 mt-1">Total classes</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-calendar-alt text-4xl"></i>
                        </div>
                    </div>
                </a>

                <!-- Account Status Card -->
                <div class="stat-card card-hover bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Account Status</p>
                            <h3 class="text-2xl font-bold mt-2"><?php echo ucfirst($user['status'] ?? 'Active'); ?></h3>
                            <p class="text-xs text-green-200 mt-1">Last login: <?php echo date('M d, Y', $_SESSION['login_time']); ?></p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-check-circle text-4xl"></i>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Actions -->
            <section class="bg-white rounded-xl shadow-lg p-6 stat-card mb-8" style="animation-delay: 0.4s">
                <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="professor_profile.php" class="group bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-4 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105 no-underline">
                        <div class="bg-white/20 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-user-edit text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold">Edit Profile</p>
                            <p class="text-xs text-blue-100">Update information</p>
                        </div>
                    </a>
                    <a href="professor_schedules.php" class="group bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-4 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105 no-underline">
                        <div class="bg-white/20 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-calendar-plus text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold">Manage Schedule</p>
                            <p class="text-xs text-purple-100">Add or edit classes</p>
                        </div>
                    </a>
                    <a href="index_chatbot.php" class="group bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white px-6 py-4 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105 no-underline">
                        <div class="bg-white/20 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-robot text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold">AI Chatbot</p>
                            <p class="text-xs text-pink-100">Ask questions</p>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Upcoming Schedules -->
            <section class="bg-white rounded-xl shadow-lg p-6 stat-card" style="animation-delay: 0.5s">
                <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-week"></i>
                    Your Schedule
                </h3>
                
                <?php if (!empty($upcoming_schedules)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($upcoming_schedules as $index => $schedule): ?>
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 border-l-4 border-purple-500" style="animation-delay: <?php echo 0.6 + ($index * 0.1); ?>s">
                                <h4 class="font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($schedule['subject'] ?? $schedule['subject_code'] ?? 'N/A'); ?></h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <p><i class="fas fa-calendar-day text-purple-500 w-5"></i> <?php echo htmlspecialchars($schedule['day'] ?? $schedule['days'] ?? 'N/A'); ?></p>
                                    <p><i class="fas fa-clock text-blue-500 w-5"></i> <?php echo htmlspecialchars($schedule['time'] ?? 'N/A'); ?></p>
                                    <p><i class="fas fa-door-open text-pink-500 w-5"></i> <?php echo htmlspecialchars($schedule['room'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-calendar-xmark text-5xl mb-4 block text-gray-300"></i>
                        <p class="text-lg font-semibold">No schedules yet</p>
                        <p class="text-sm">Add your teaching schedules to get started</p>
                        <a href="professor_schedules.php" class="inline-block mt-4 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i>Add Schedule
                        </a>
                    </div>
                <?php endif; ?>
            </section>

        </div>
    </main>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Logout?',
                text: "Are you sure you want to logout?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }

        // Show welcome message on first login
        <?php if (isset($_GET['welcome'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Welcome!',
            text: 'You have successfully logged in to the Professor Portal',
            confirmButtonColor: '#8b5cf6',
            timer: 3000
        });
        window.history.replaceState({}, document.title, window.location.pathname);
        <?php endif; ?>

        // Show timeout message
        <?php if (isset($_GET['timeout'])): ?>
        Swal.fire({
            icon: 'warning',
            title: 'Session Expired',
            text: 'Your session has expired. Please login again.',
            confirmButtonColor: '#8b5cf6'
        });
        window.history.replaceState({}, document.title, window.location.pathname);
        <?php endif; ?>
    </script>

</body>
</html>
