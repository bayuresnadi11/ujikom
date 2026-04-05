<script>
    // Global Variables
    let currentTab = 'home';
    let selectedVenue = null;
    
    // Modal functions
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function closeModalOnOverlay(e, id) {
        if (e.target.classList.contains('modal-overlay')) {
            closeModal(id);
        }
    }
    
    function switchModal(fromId, toId) {
        closeModal(fromId);
        setTimeout(() => openModal(toId), 300);
    }
    
    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        // Create toast if not exists
        if (!toast) {
            const toastDiv = document.createElement('div');
            toastDiv.id = 'toast';
            toastDiv.className = 'toast';
            toastDiv.innerHTML = `<i class="fas fa-check-circle"></i><span id="toast-message">${message}</span>`;
            document.body.appendChild(toastDiv);
        }
        
        // Set icon based on type
        let icon = 'fa-check-circle';
        if (type === 'error') icon = 'fa-exclamation-circle';
        if (type === 'info') icon = 'fa-info-circle';
        
        const toastElement = document.getElementById('toast');
        toastElement.innerHTML = `<i class="fas ${icon}"></i><span id="toast-message">${message}</span>`;
        toastElement.className = `toast ${type}`;
        
        setTimeout(() => {
            toastElement.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            toastElement.classList.remove('show');
        }, 3000);
    }
    
    // Tab switching
    function switchTab(tab) {
        currentTab = tab;
        const navItems = document.querySelectorAll('.bottom-nav .nav-item');
        navItems.forEach(item => item.classList.remove('active'));
        
        // Set active based on clicked item
        if (tab === 'home') navItems[0]?.classList.add('active');
        if (tab === 'menu') navItems[1]?.classList.add('active');
        if (tab === 'explore') navItems[2]?.classList.add('active');
        if (tab === 'chat') navItems[3]?.classList.add('active');
        if (tab === 'profile') navItems[4]?.classList.add('active');

    }
    
// ========================================
// FILTER BY CATEGORY
// ========================================
function filterByCategory(categoryId) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    const container = document.getElementById('venues-container');
    if (!container) return;

    // Filter cards
    const cards = container.querySelectorAll('.category-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        // This is for the initial empty state card from server, which shouldn't be filtered
        if (card.classList.contains('full-width-card') && !card.classList.contains('filter-empty')) {
            card.style.display = 'none';
            return;
        }

        const cardCategory = card.getAttribute('data-category-id');
        if (categoryId === 'all' || cardCategory == categoryId) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state for filtering
    let emptyState = container.querySelector('.filter-empty');
    if (visibleCount === 0 && cards.length > 0) {
        if (!emptyState) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'category-card full-width-card filter-empty';
            emptyDiv.innerHTML = `
                <div class="category-name">Tidak ada lapangan untuk kategori ini</div>
            `;
            container.appendChild(emptyDiv);
        }
    } else {
        if (emptyState) emptyState.remove();
    }
}

    
    function getTabName(tab) {
        const names = {
            'home': 'Beranda',
            'menu': 'Menu',
            'explore': 'Jelajah',
            'chat': 'Chat',
            'profile': 'Profil'
        };
        return names[tab] || tab;
    }
    
    // Search functionality
    function handleSearch() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput.value.trim()) {
            showToast(`Mencari: "${searchInput.value}"`, 'info');
            // In real app, this would trigger search API
            searchInput.value = '';
        }
    }
    
    // Sport selection
    function selectSport(sport) {
        showToast(`Memilih olahraga ${sport}`, 'success');
        // In real app, this would filter venues by sport
    }
    
    // Venue booking
    function bookVenue(venueName) {
        selectedVenue = venueName;
        showToast(`Membooking ${venueName}`, 'info');
        openModal('bookingModal');
    }
    
    function showVenueDetail(venueName) {
        showToast(`Melihat detail ${venueName}`, 'info');
        // In real app, this would open venue detail page
    }
    
    // Event joining
    function joinEvent(eventName) {
        showToast(`Bergabung dengan event ${eventName}`, 'success');
        // In real app, this would add user to event
    }
    
    // View all functions
    function showAllSports() {
        showToast('Membuka semua jenis olahraga', 'info');
    }
    
    function showAllEvents() {
        showToast('Membuka semua event', 'info');
    }
    
    function showAllTestimonials() {
        showToast('Membuka semua testimoni', 'info');
    }
    
    // Form handlers
    function handleLogin(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('loginSubmit');
        if (!submitBtn) return;
        
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.innerHTML = 'Memproses... <div class="loading"></div>';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            showToast('Login berhasil! Selamat datang kembali.', 'success');
            closeModal('loginModal');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 1500);
    }
    
    function handleRegister(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('registerSubmit');
        if (!submitBtn) return;
        
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.innerHTML = 'Mendaftarkan... <div class="loading"></div>';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            showToast('Pendaftaran berhasil! Selamat bergabung dengan SewaLap.', 'success');
            closeModal('registerModal');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Auto open login modal after registration
            setTimeout(() => {
                openModal('loginModal');
            }, 500);
        }, 2000);
    }
    
    function handleVenueRegistration(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('venueSubmit');
        if (!submitBtn) return;
        
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.innerHTML = 'Mengirim... <div class="loading"></div>';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            showToast('Pendaftaran venue berhasil! Tim kami akan menghubungi dalam 1x24 jam.', 'success');
            closeModal('venueModal');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    }
    
    function handleBooking(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('bookingSubmit');
        if (!submitBtn) return;
        
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.innerHTML = 'Memproses... <div class="loading"></div>';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            showToast('Booking berhasil! Silakan lanjutkan pembayaran.', 'success');
            closeModal('bookingModal');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Simulate payment process
            setTimeout(() => {
                showToast('Pembayaran berhasil! Tiket telah dikirim ke email Anda.', 'success');
            }, 2000);
        }, 2000);
    }
    
    function showNotification() {
        showToast('Anda memiliki 5 notifikasi baru', 'info');
    }
    
    function showForgotPassword() {
        showToast('Fitur lupa kata sandi sedang dalam pengembangan', 'info');
    }
    
    function socialLogin(provider) {
        showToast(`Login dengan ${provider} sedang dalam pengembangan`, 'info');
    }
    
    // Initialize date pickers
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date untuk booking (hari ini)
        const today = new Date().toISOString().split('T')[0];
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.min = today;
            if (!input.value) {
                // Set default to tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                input.value = tomorrow.toISOString().split('T')[0];
            }
        });
        
        // Initialize booking total if exists
        const bookingDuration = document.getElementById('bookingDuration');
        const bookingVenue = document.getElementById('bookingVenue');
        
        if (bookingDuration && bookingVenue) {
            updateBookingTotal();
            bookingDuration.addEventListener('change', updateBookingTotal);
            bookingVenue.addEventListener('change', updateBookingTotal);
        }
        
        // Search on enter
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    handleSearch();
                }
            });
        }
        
        // Initialize active tab
        switchTab('home');
    });
    
    // Update booking total
    function updateBookingTotal() {
        const venueSelect = document.getElementById('bookingVenue');
        const durationSelect = document.getElementById('bookingDuration');
        const totalElement = document.getElementById('bookingTotal');
        
        if (!venueSelect || !durationSelect || !totalElement) return;
        
        let basePrice = 180000; // Default price
        
        if (venueSelect.value === 'badminton') basePrice = 95000;
        if (venueSelect.value === 'basket') basePrice = 120000;
        if (venueSelect.value === 'tenis') basePrice = 150000;
        
        const duration = parseInt(durationSelect.value) || 1;
        const total = basePrice * duration;
        
        // Format to Indonesian Rupiah
        totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }
    
    // Prevent form submission on demo
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.classList.contains('prevent-demo')) {
                this.classList.add('prevent-demo');
            }
        });
    });
</script>