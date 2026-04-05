<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<div class="sidebar">
    <!-- Sidebar Logo/Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-grid-fill"></i>
        </div>
        <div class="brand-text">
            <h3>Admin Panel</h3>
            <p>Management System</p>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-section">
                <span class="section-title">Main Menu</span>
            </li>
            <li>
                <a href="{{ route('admin.dashboard.index') }}"
                    class="{{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}"
                    data-tooltip="Dashboard">
                    <div class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <span class="nav-text">Dashboard</span>
                    <div class="active-indicator"></div>
                </a>
            </li>
            
            <li>
                <a href="{{ route('chat.index') }}"
                    class="{{ request()->routeIs('chat.*') ? 'active' : '' }}"
                    data-tooltip="Pesan">
                    <div class="nav-icon">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <span class="nav-text">Pesan</span>
                    <div class="active-indicator"></div>
                </a>
            </li>
            
            <li class="nav-section">
                <span class="section-title">Management</span>
            </li>
            <li>
                <a href="{{ route('admin.category.index') }}"
                    class="{{ request()->routeIs('admin.category.*') ? 'active' : '' }}"
                    data-tooltip="category">
                    <div class="nav-icon">
                        <i class="bi bi-list-ul"></i>
                    </div>
                    <span class="nav-text">Daftar Kategori</span>
                    <div class="active-indicator"></div>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.community.index') }}"
                    class="{{ request()->routeIs('admin.community.*') ? 'active' : '' }}"
                    data-tooltip="Community">
                    <div class="nav-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <span class="nav-text">Daftar Komunitas</span>
                    <div class="active-indicator"></div>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.user.index') }}"
                    class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}"
                    data-tooltip="Users">
                    <div class="nav-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <span class="nav-text">Daftar Users</span>
                    <div class="active-indicator"></div>
                </a>
            </li>

                        <li>
                <a href="{{ route('admin.daftar_admin.index') }}"
                    class="{{ request()->routeIs('admin.daftar_admin.*') ? 'active' : '' }}"
                    data-tooltip="Daftar Admin">
                    <div class="nav-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <span class="nav-text">Daftar Admin</span>
                    <div class="active-indicator"></div>
                </a>
            </li>

            
            <li class="nav-section">
                <span class="section-title">Permissions</span>
            </li>

            <li>
                <a href="{{ route('admin.rolerequest.index') }}"
                    class="{{ request()->routeIs('admin.rolerequest.*') ? 'active' : '' }}"
                    data-tooltip="Role Requests">
                    <div class="nav-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <span class="nav-text">Approve/Reject Role</span>
                    <div class="active-indicator"></div>
                </a>
            </li>
   <li class="nav-section">
                <span class="section-title">Pengaturan Aplikasi</span>
            </li>

            <li>
                <a href="{{ route('admin.setting.index') }}"
                    class="{{ request()->routeIs('admin.setting.*') ? 'active' : '' }}"
                    data-tooltip="Setting">
                    <div class="nav-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <span class="nav-text">Pengaturan</span>
                    <div class="active-indicator"></div>
                </a>
            </li>

            <!-- Logout (hidden form) -->
            <li class="mt-4" style="display: none;">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="footer-content">
            <div class="footer-text">
                <!-- Footer content -->
            </div>
        </div>
    </div>
</div>

<script>
// Add ripple effect on click
document.querySelectorAll('.sidebar-nav a').forEach(link => {
    link.addEventListener('click', function(e) {
        // Create ripple element
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        
        // Get click position
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        
        this.appendChild(ripple);
        
        // Remove ripple after animation
        setTimeout(() => ripple.remove(), 600);
    });
});

// Smooth scroll to top when clicking dashboard
document.querySelector('a[href*="dashboard.index"]')?.addEventListener('click', function(e) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Add hover effect sound (optional - comment out if not needed)
document.querySelectorAll('.sidebar-nav a').forEach(link => {
    link.addEventListener('mouseenter', function() {
        // You can add subtle feedback here if needed
        this.style.transform = 'translateX(5px)';
    });
    
    link.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});
</script>