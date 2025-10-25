<!-- Overlay for mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-[45] hidden" onclick="closeMobileSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 h-screen bg-gradient-to-b from-blue-600 via-purple-600 to-pink-600 text-white shadow-xl z-50">
    <div class="h-full overflow-y-auto">
        <!-- Sidebar Header with Toggle -->
        <div class="flex items-center justify-between p-4 border-b border-white/20">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <div class="overflow-hidden">
                    <h2 class="font-bold text-sm truncate">Chatbot System</h2>
                    <p class="text-xs text-white/70 truncate">Admin Portal</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200 flex-shrink-0">
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="p-4">
        <nav class="space-y-2">
            <a href="admin.php" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 backdrop-blur-sm transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : ''; ?>">
                <i class="fas fa-home text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="professors.php" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 backdrop-blur-sm transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'professors.php' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher text-lg"></i>
                <span class="font-medium">Professors</span>
            </a>
            <a href="students.php" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 backdrop-blur-sm transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-graduate text-lg"></i>
                <span class="font-medium">Students Management</span>
            </a>
            
            <!-- Divider -->
            <div class="my-4 border-t border-white/20"></div>
            
            <!-- Quick Stats in Sidebar -->
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mt-4">
                <p class="text-xs text-white/70 mb-2">Quick Stats</p>
                <div class="space-y-2 text-sm">
                    <?php
                    $conn_sidebar = new mysqli("localhost", "root", "", "chatbot");
                    if (!$conn_sidebar->connect_error) {
                        $prof_count = $conn_sidebar->query("SELECT COUNT(*) as count FROM professors")->fetch_assoc()['count'];
                        $sched_count = $conn_sidebar->query("SELECT COUNT(*) as count FROM schedules")->fetch_assoc()['count'];
                        $user_count = $conn_sidebar->query("SELECT COUNT(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];
                        $conn_sidebar->close();
                    ?>
                    <div class="flex justify-between">
                        <span>Professors:</span>
                        <span class="font-bold"><?php echo $prof_count ?? 0; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Schedules:</span>
                        <span class="font-bold"><?php echo $sched_count ?? 0; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Students:</span>
                        <span class="font-bold"><?php echo $user_count ?? 0; ?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </div>
    </div>
</aside>
