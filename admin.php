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
    <title>Admin Dashboard - Chatbot System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php 
    $page_title = "Admin Panel";
    $page_subtitle = "Chatbot System";
    include 'includes/styles.php'; 
    ?>
    <style>
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Sidebar - Simple responsive approach */
        #sidebar {
            width: 256px;
            transition: transform 0.3s ease;
        }
        
        .sidebar-hidden {
            transform: translateX(-100%);
        }
        
        /* Mobile: sidebar slides in/out */
        @media (max-width: 1023px) {
            #sidebar {
                transform: translateX(-100%);
            }
            
            #sidebar.sidebar-visible {
                transform: translateX(0);
            }
            
            #topNav {
                left: 0 !important;
            }
            
            #mainContent {
                margin-left: 0 !important;
            }
        }
        
        /* Desktop: sidebar always visible */
        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
            }
            
            #topNav {
                left: 256px !important;
            }
            
            #mainContent {
                margin-left: 256px !important;
            }
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .nav-item {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: white;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: scaleY(1);
        }
        
        .stat-card {
            animation: fadeIn 0.6s ease-out;
        }
        
        @media (max-width: 768px) {
            .sidebar-open {
                animation: slideIn 0.3s ease-out;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    <?php include 'includes/topnav.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main id="mainContent" class="transition-all duration-300 pt-20 lg:pt-24 p-4 sm:p-6 lg:p-8">
        <!-- Welcome Header -->
        <header class="mb-8 stat-card">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋
                </h1>
                <p class="text-gray-600 mt-1">Manage professors and their schedules efficiently</p>
            </div>
        </header>

        <!-- Dashboard Section -->
        <section>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
                <?php
                $conn = new mysqli("localhost", "root", "", "chatbot");
                if ($conn->connect_error) die("Connection failed");
                
                $prof_count = $conn->query("SELECT COUNT(*) as count FROM professors")->fetch_assoc()['count'];
                $sched_count = $conn->query("SELECT COUNT(*) as count FROM schedules")->fetch_assoc()['count'];
                $user_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];
                $conn->close();
                ?>
                
                <a href="professors.php" class="stat-card card-hover bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white cursor-pointer group no-underline" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Professors</p>
                            <h3 class="text-4xl font-bold mt-2"><?php echo $prof_count; ?></h3>
                            <p class="text-xs text-blue-200 mt-1">Click to manage</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chalkboard-teacher text-4xl"></i>
                        </div>
                    </div>
                </a>

                <a href="professors.php" class="stat-card card-hover bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white cursor-pointer group no-underline" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Schedules</p>
                            <h3 class="text-4xl font-bold mt-2"><?php echo $sched_count; ?></h3>
                            <p class="text-xs text-purple-200 mt-1">View schedules</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-calendar-alt text-4xl"></i>
                        </div>
                    </div>
                </a>

                <a href="students.php" class="stat-card card-hover bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white cursor-pointer group no-underline" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-pink-100 text-sm font-medium">Total Students</p>
                            <h3 class="text-4xl font-bold mt-2"><?php echo $user_count; ?></h3>
                            <p class="text-xs text-pink-200 mt-1">View students</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-user-graduate text-4xl"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card" style="animation-delay: 0.4s">
                <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="professors.php" class="group bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-4 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105 no-underline">
                        <div class="bg-white/20 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-chalkboard-teacher text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold">Manage Professors</p>
                            <p class="text-xs text-blue-100">Add, edit, or delete</p>
                        </div>
                    </a>
                    <a href="students.php" class="group bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-4 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105 no-underline">
                        <div class="bg-white/20 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-user-graduate text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold">View Students</p>
                            <p class="text-xs text-purple-100">Student records</p>
                        </div>
                    </a>
                    <a href="view_schedule.php" class="group bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white px-6 py-4 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105 no-underline">
                        <div class="bg-white/20 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-calendar-check text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold">View Schedules</p>
                            <p class="text-xs text-pink-100">All schedules</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card mt-8" style="animation-delay: 0.5s">
                <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line"></i>
                    System Overview
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
                            <div class="bg-blue-500 text-white p-3 rounded-full">
                                <i class="fas fa-database text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Database Status</p>
                                <p class="text-lg font-bold text-gray-800">Connected</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg">
                            <div class="bg-purple-500 text-white p-3 rounded-full">
                                <i class="fas fa-server text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">System Status</p>
                                <p class="text-lg font-bold text-gray-800">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <div class="inline-block p-6 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mb-3">
                                <i class="fas fa-check-circle text-5xl text-green-500"></i>
                            </div>
                            <p class="text-lg font-semibold text-gray-700">All Systems Operational</p>
                            <p class="text-sm text-gray-500">Chatbot System Running</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/scripts.php'; ?>

</body>
</html>
