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
