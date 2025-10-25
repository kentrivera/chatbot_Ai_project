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
        <!-- Students List -->
        <section id="students-section" class="tab-content">
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card" style="animation-delay: 0.1s">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent flex items-center gap-2">
                            <i class="fas fa-user-graduate"></i>
                            Registered Students
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">All students registered in the system</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Registration Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Open a direct DB connection (avoid using db_config.php here as it sets JSON headers)
                            $conn = new mysqli("localhost", "root", "", "chatbot");
                            if ($conn->connect_error) {
                                echo "<tr><td colspan='5' class='px-6 py-8 text-center text-red-600'>" .
                                     "<i class='fas fa-triangle-exclamation mr-2'></i>Could not connect to database." .
                                     "</td></tr>";
                            } else {
                                $result = $conn->query("SELECT id, username, first_name, last_name, created_at FROM users WHERE role='student' ORDER BY id DESC");
                                if ($result && $result->num_rows > 0) {
                                    $count = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        $count++;
                                        $delay = $count * 0.05;
                                        $fullName = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
                                        echo "<tr class='hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200 stat-card' style='animation-delay: {$delay}s'>";
                                        echo "<td class='px-6 py-4'><span class='text-sm font-medium text-gray-900'>#" . (int)$row['id'] . "</span></td>";
                                        echo "<td class='px-6 py-4'><div class='flex items-center gap-2'><i class='fas fa-user text-purple-500'></i><span class='text-sm font-medium text-gray-900'>" . htmlspecialchars($row['username']) . "</span></div></td>";
                                        echo "<td class='px-6 py-4'><div class='text-sm text-gray-700'>" . htmlspecialchars($fullName !== '' ? $fullName : '—') . "</div></td>";
                                        $created = !empty($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : '—';
                                        echo "<td class='px-6 py-4'><div class='text-sm text-gray-500'>" . htmlspecialchars($created) . "</div></td>";
                                        echo "<td class='px-6 py-4'><span class='inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800'><i class='fas fa-check-circle mr-1'></i> Active</span></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='px-6 py-8 text-center text-gray-500'>No students registered yet</td></tr>";
                                }
                                $conn->close();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/scripts.php'; ?>

</body>
</html>
