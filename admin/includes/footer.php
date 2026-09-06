</div> <!-- /main-content -->
</div> <!-- /admin-layout -->

<script>
    // Inisialisasi Ikon Lucide
    lucide.createIcons();

    // Fungsi Toggle Sidebar (Minimize & Responsive Mobile)
    function toggleSidebar() {
        const body = document.body;
        const overlay = document.getElementById('sidebarOverlay');
        
        // Di layar HP/Mobile
        if (window.innerWidth <= 768) {
            body.classList.toggle('mobile-sidebar-open');
        } else {
            // Di layar Desktop (Minimize)
            body.classList.toggle('sidebar-minimized');
        }
    }
</script>
</body>
</html>