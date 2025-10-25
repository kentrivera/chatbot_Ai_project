<!-- Top Navigation -->
<nav id="topNav" class="fixed top-0 left-0 right-0 bg-white shadow-lg z-40 border-b border-gray-200 transition-all duration-300">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left: Menu Button (Mobile) + Logo -->
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200 p-2 rounded-lg active:scale-95" aria-label="Toggle Menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center gap-2">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg p-2">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent"><?php echo $page_title ?? 'Admin Panel'; ?></h1>
                        <p class="text-xs text-gray-500"><?php echo $page_subtitle ?? 'Chatbot System'; ?></p>
                    </div>
                </div>
            </div>

            <!-- Right: User Info & Logout -->
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-3 bg-gradient-to-r from-blue-50 to-purple-50 px-4 py-2 rounded-xl border border-purple-100">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                        <?php 
                            $initials = strtoupper(substr($_SESSION['username'], 0, 2));
                            echo $initials;
                        ?>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
                <button onclick="confirmLogout()" class="relative bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-3 py-2 rounded-lg flex items-center gap-2 transition-all duration-300 shadow-sm hover:shadow-lg hover:scale-105 group overflow-hidden">
                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <i class="fas fa-sign-out-alt text-xs relative z-10 group-hover:rotate-12 transition-transform duration-300"></i>
                    <span class="hidden sm:inline text-xs font-medium relative z-10">Logout</span>
                </button>
            </div>
        </div>
    </div>
</nav>
