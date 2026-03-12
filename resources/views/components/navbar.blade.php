<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<nav class="admin-navbar">
    <div class="admin-navbar-inner">
        <!-- Hamburger Menu for Mobile -->
        <button class="hamburger-btn" onclick="toggleSidebar()" aria-label="Toggle Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <h2 class="admin-title">
            <i class="bi bi-shield-check"></i>
            <span>Admin Dashboard</span>
        </h2>

        <div class="navbar-right">
            <!-- Search Bar -->
        

            <!-- Notifications -->
      
            <!-- User Dropdown -->
            <div class="user-dropdown">
                <button onclick="toggleUserMenu()" class="user-btn">
                    <div class="user-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <span class="user-name">Admin</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </button>

                <div id="userMenu" class="user-menu">
                    <div class="user-menu-header">
                        <div class="user-avatar-large">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="user-info">
                            <div class="user-menu-name">Administrator</div>
                            <div class="user-menu-email">admin@example.com</div>
                        </div>
                    </div>
                    <div class="user-menu-divider"></div>
                    <a href="{{ route('admin.profile.index') }}" class="user-menu-item">
                        <i class="bi bi-person"></i>
                        <span>Profile</span>
                    </a>
                
                    <div class="user-menu-divider"></div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="user-menu-item logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Notification Dropdown -->
        <div id="notificationMenu" class="notification-dropdown">
            <div class="notification-header">
                <h4>Notifications</h4>
                <a href="#" class="mark-read">Mark all as read</a>
            </div>
            <div class="notification-list">
                <a href="#" class="notification-item unread">
                    <div class="notification-icon new">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text">New user registered</div>
                        <div class="notification-time">5 minutes ago</div>
                    </div>
                </a>
                <a href="#" class="notification-item unread">
                    <div class="notification-icon pending">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text">Role request pending</div>
                        <div class="notification-time">1 hour ago</div>
                    </div>
                </a>
                <a href="#" class="notification-item">
                    <div class="notification-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text">Community approved</div>
                        <div class="notification-time">3 hours ago</div>
                    </div>
                </a>
            </div>
            <div class="notification-footer">
                <a href="#" class="view-all">View all notifications</a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    const notifMenu = document.getElementById('notificationMenu');
    
    // Close notification menu if open
    if (notifMenu.classList.contains('show')) {
        notifMenu.classList.remove('show');
    }
    
    menu.classList.toggle('show');
}

function toggleNotifications() {
    const menu = document.getElementById('notificationMenu');
    const userMenu = document.getElementById('userMenu');
    
    // Close user menu if open
    if (userMenu.classList.contains('show')) {
        userMenu.classList.remove('show');
    }
    
    menu.classList.toggle('show');
}

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const hamburger = document.querySelector('.hamburger-btn');
    
    sidebar.classList.toggle('mobile-active');
    hamburger.classList.toggle('active');
    
    // Add overlay on mobile
    if (sidebar.classList.contains('mobile-active')) {
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        overlay.onclick = toggleSidebar;
        document.body.appendChild(overlay);
    } else {
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay) overlay.remove();
    }
}

// Close menus when clicking outside
document.addEventListener('click', function(e) {
    const userMenu = document.getElementById('userMenu');
    const notifMenu = document.getElementById('notificationMenu');
    const userButton = e.target.closest('.user-btn');
    const notifButton = e.target.closest('.icon-btn');
    const userMenuContent = e.target.closest('.user-menu');
    const notifMenuContent = e.target.closest('.notification-dropdown');
    
    if (!userButton && !userMenuContent && userMenu.classList.contains('show')) {
        userMenu.classList.remove('show');
    }
    
    if (!notifButton && !notifMenuContent && notifMenu.classList.contains('show')) {
        notifMenu.classList.remove('show');
    }
});

// Search functionality
document.querySelector('.search-input')?.addEventListener('focus', function() {
    this.parentElement.classList.add('focused');
});

document.querySelector('.search-input')?.addEventListener('blur', function() {
    if (!this.value) {
        this.parentElement.classList.remove('focused');
    }
});
</script>