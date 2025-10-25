<script>
    // Simple Sidebar Toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        console.log('Toggle sidebar called. Width:', window.innerWidth, 'Sidebar:', sidebar, 'Overlay:', overlay);
        
        // Only works on mobile
        if (window.innerWidth < 1024) {
            sidebar.classList.toggle('sidebar-visible');
            overlay.classList.toggle('hidden');
            console.log('Sidebar toggled. Visible:', sidebar.classList.contains('sidebar-visible'));
        } else {
            console.log('Desktop mode - sidebar always visible');
        }
    }

    function closeMobileSidebar() {
        if (window.innerWidth < 1024) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('sidebar-visible');
            overlay.classList.add('hidden');
        }
    }

    // Close sidebar when resizing to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            document.getElementById('sidebarOverlay').classList.add('hidden');
            document.getElementById('sidebar').classList.remove('sidebar-visible');
        }
    });

    // Logout Confirmation with SweetAlert
    function confirmLogout() {
        Swal.fire({
            title: '<span class="text-2xl">👋 Leaving so soon?</span>',
            html: '<p class="text-gray-600 text-sm">Your amazing work will be waiting when you return!</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Stay here',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'px-6 py-2.5 rounded-lg font-medium',
                cancelButton: 'px-6 py-2.5 rounded-lg font-medium'
            },
            buttonsStyling: false,
            width: '400px',
            didOpen: () => {
                const confirmBtn = Swal.getConfirmButton();
                const cancelBtn = Swal.getCancelButton();
                
                confirmBtn.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%)';
                confirmBtn.style.color = 'white';
                confirmBtn.style.marginRight = '8px';
                
                cancelBtn.style.background = '#e5e7eb';
                cancelBtn.style.color = '#374151';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Logging out...',
                    html: '<p class="text-gray-600 text-sm">See you soon! 🌟</p>',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-2xl'
                    },
                    width: '350px'
                }).then(() => {
                    window.location.href = 'logout.php';
                });
            }
        });
    }
</script>
