
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menu-toggle');
        // REMOVED: const menuIcon = document.getElementById('menu-icon'); // Removed this line
        const sidebarCloseBtn = document.getElementById('sidebar-close-btn'); // ADDED: New button in sidebar

        // Backdrop
        let backdrop = document.querySelector('.sidebar-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop';
            document.body.appendChild(backdrop);
        }

        function openSidebar() {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
            // REMOVED menuIcon logic
            if (menuToggle) {
                menuToggle.setAttribute('aria-expanded', 'true');
            }
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
            // REMOVED menuIcon logic
            if (menuToggle) {
                menuToggle.setAttribute('aria-expanded', 'false');
            }
            document.body.style.overflow = '';
        }

        function toggleSidebar() {
            if (window.innerWidth < 992) {
                if (sidebar.classList.contains('show')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
        }

        if (menuToggle) {
            menuToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleSidebar();
            });
        }
        
        // This is the listener that makes the 'x' button work!
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeSidebar(); // Calls the clean closeSidebar function
            });
        }
        
        // Only close when clicking backdrop, not anywhere on screen
        if (backdrop) {
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) {
                    closeSidebar();
                }
            });
        }

        // Don't close sidebar when clicking inside it
        if (sidebar) {
            sidebar.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                closeSidebar();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
                // REMOVED menuIcon logic here as well
                document.body.style.overflow = '';
            }
        });
    });
