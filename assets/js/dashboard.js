document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');

    // Toggle Sidebar on Mobile
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent closing immediately
            sidebar.classList.toggle('show');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 992) {
            if (sidebar.classList.contains('show') && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Handle Window Resize
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show');
        }
    });
});
