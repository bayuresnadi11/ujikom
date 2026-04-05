<script>
    // Global Variables
    let currentUser = {
        name: "{{ $user->name }}",
        phone: "{{ $user->phone }}",
        address: "{{ $user->address }}",
        business_name: "{{ $user->business_name }}",
    };
    
    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        // Set icon based on type
        let icon = 'fa-check-circle';
        if (type === 'error') icon = 'fa-exclamation-circle';
        if (type === 'info') icon = 'fa-info-circle';
        
        toast.innerHTML = `<i class="fas ${icon}"></i><span id="toast-message">${message}</span>`;
        toast.className = `toast ${type}`;
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }
    
    // Fungsi untuk fitur yang dihapus (diubah menjadi fungsi placeholder)
    function manageVenues() {
        showToast('Membuka halaman kelola lapangan', 'info');
        window.location.href = "{{ route('landowner.lapangan.index') }}";
    }
    
    function manageBookings() {
        showToast('Membuka halaman booking manager', 'info');
        window.location.href = "{{ route('landowner.profile') }}";
    }
    
    function showDetail(type) {
        const messages = {
            'pendapatan': 'Melihat detail pendapatan',
            'booking': 'Melihat detail booking',
            'lapangan': 'Melihat detail lapangan',
            'rating': 'Melihat detail rating'
        };
        showToast(messages[type] || 'Melihat detail', 'info');
        
        // Redirect ke halaman statistik jika diperlukan
        if (type === 'pendapatan') {
            setTimeout(() => {
                window.location.href = "#";
            }, 500);
        } else if (type === 'booking') {
            setTimeout(() => {
                window.location.href = "#";
            }, 500);
        } else if (type === 'lapangan') {
            setTimeout(() => {
                window.location.href = "#";
            }, 500);
        }
    }
    
    // Action functions
    function showNotification() {
        showToast('Anda memiliki 3 notifikasi baru', 'info');
        // Redirect ke halaman notifikasi
        setTimeout(() => {
            window.location.href = "#";
        }, 500);
    }
    
    function openHelpCenter() {
        showToast('Membuka pusat bantuan landowner', 'info');
        setTimeout(() => {
            window.location.href = "#";
        }, 500);
    }
    
    function openContactSupport() {
        showToast('Membuka dukungan teknis', 'info');
        setTimeout(() => {
            window.location.href = "mailto:support@sewalap.com?subject=Dukungan Teknis Landowner";
        }, 500);
    }
    
    function logoutLandowner() {
        if (confirm('Apakah Anda yakin ingin keluar dari akun landowner?')) {
            showToast('Sedang keluar...', 'info');
            
            // Create a form and submit it for logout
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('logout') }}";
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
    // Function untuk switch role ke buyer
    function directSwitchToBuyer() {
        if (confirm('Apakah Anda yakin ingin beralih ke mode buyer?\nAnda akan diarahkan ke dashboard buyer.')) {
            // Buat form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('switch.to.buyer') }}"; // Pakai route baru
            
            // CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            form.appendChild(csrfToken);
            
            // Submit
            document.body.appendChild(form);
            form.submit();
        }

        // if (confirm('Apakah Anda yakin ingin beralih ke mode buyer?\nAnda akan diarahkan ke dashboard buyer.')) {
        //     showToast('Mengalihkan ke mode buyer...', 'info');
            
        //     // Kirim request ke server untuk switch role
        //     fetch("{{ route('switch.to.buyer') }}", {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             // Redirect ke dashboard buyer
        //             window.location.href = data.redirect_url || "{{ url('/buyer/dashboard') }}";
        //         } else {
        //             showToast(data.message || 'Gagal beralih ke mode buyer', 'error');
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //         showToast('Terjadi kesalahan saat beralih role', 'error');
        //     });
        // }
    }
    
    // Load stats when page loads
    function loadStats() {
        fetch("{{ route('landowner.profile.stats') }}")
            .then(response => response.json())
            .then(data => {
                if (data.success && data.stats) {
                    // Update stats on the page
                    updateStatsUI(data.stats);
                } else {
                    // Set default values if API returns error
                    updateStatsUI({
                        monthly_revenue: 0,
                        active_bookings: 0,
                        total_venues: 0,
                        average_rating: 0
                    });
                }
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                // Set default values if loading fails
                updateStatsUI({
                    monthly_revenue: 0,
                    active_bookings: 0,
                    total_venues: 0,
                    average_rating: 0
                });
            });
    }

    // Update stats UI
    function updateStatsUI(stats) {
        const revenueElement = document.getElementById('pendapatanValue');
        const bookingElement = document.getElementById('bookingValue');
        const venueElement = document.getElementById('lapanganValue');
        const ratingElement = document.getElementById('ratingValue');
        
        if (revenueElement) revenueElement.textContent = formatCurrency(stats.monthly_revenue || 0);
        if (bookingElement) bookingElement.textContent = stats.active_bookings || 0;
        if (venueElement) venueElement.textContent = stats.total_venues || 0;
        if (ratingElement) ratingElement.textContent = (stats.average_rating || 0).toFixed(1);
    }

    // Format currency helper
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Update profile UI with new data
    function updateProfileUI(userData) {
        // Update elements if they exist
        const nameElement = document.getElementById('profileName');
        const phoneElement = document.getElementById('detailPhone');
        const addressElement = document.getElementById('detailAddress');
        const businessElement = document.getElementById('detailBusiness');
        
        if (nameElement && userData.name) nameElement.textContent = userData.name;
        if (phoneElement && userData.phone) phoneElement.textContent = userData.phone;
        if (addressElement && userData.address) addressElement.textContent = userData.address;
        if (businessElement && userData.business_name) businessElement.textContent = userData.business_name;
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Demo auto welcome notification
        setTimeout(() => {
            showToast('Selamat datang di dashboard landowner!', 'success');
        }, 500);
        
        // Load stats
        loadStats();
        
        // Setup auto-refresh setiap 30 detik
        setInterval(refreshProfileData, 30000);
        
        // Setup auto-refresh stats setiap 60 detik
        setInterval(loadStats, 1000);
    });
// ================= SIMPLE LOGOUT CONFIRM MODAL =================
function showLogoutConfirm() {
    // Cek jika modal sudah ada
    if (document.getElementById('logoutConfirmModal')) {
        return;
    }

    // Buat modal overlay
    const modalOverlay = document.createElement('div');
    modalOverlay.id = 'logoutConfirmModal';
    modalOverlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 20px;
        animation: fadeIn 0.2s ease;
    `;

    // Buat modal content
    const modalContent = document.createElement('div');
    modalContent.style.cssText = `
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 320px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        animation: scaleIn 0.3s ease;
    `;

    // Icon
    const iconDiv = document.createElement('div');
    iconDiv.style.cssText = `
        padding: 24px 24px 16px;
        text-align: center;
        color: #E74C3C;
        font-size: 48px;
    `;
    iconDiv.innerHTML = '<i class="fas fa-sign-out-alt"></i>';

    // Message
    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
        padding: 0 24px;
        text-align: center;
        margin-bottom: 24px;
    `;

    const title = document.createElement('h3');
    title.style.cssText = `
        font-size: 18px;
        color: #333;
        margin-bottom: 8px;
        font-weight: 600;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    `;
    title.textContent = 'Yakin keluar dari SewaLap?';

    const subtitle = document.createElement('p');
    subtitle.style.cssText = `
        font-size: 14px;
        color: #666;
        line-height: 1.4;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    `;
    subtitle.textContent = 'Anda akan keluar dari akun pemilik Anda.';

    messageDiv.appendChild(title);
    messageDiv.appendChild(subtitle);

    // Action buttons
    const actionsDiv = document.createElement('div');
    actionsDiv.style.cssText = `
        display: flex;
        gap: 12px;
        padding: 0 24px 24px;
    `;

    // Cancel button
    const cancelBtn = document.createElement('button');
    cancelBtn.style.cssText = `
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        border: 2px solid #FFE4E8;
        background: white;
        color: #8B1538;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    `;
    cancelBtn.textContent = 'Batal';
    cancelBtn.onclick = function() {
        closeLogoutModal();
    };

    // Logout button
    const logoutBtn = document.createElement('button');
    logoutBtn.style.cssText = `
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        border: none;
        background: #E74C3C;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    `;
    logoutBtn.textContent = 'Keluar';
    logoutBtn.onclick = function() {
        document.getElementById('logoutForm').submit();
    };

    // Hover effects
    cancelBtn.addEventListener('mouseenter', () => {
        cancelBtn.style.background = '#f7fdf9';
        cancelBtn.style.transform = 'translateY(-1px)';
    });
    cancelBtn.addEventListener('mouseleave', () => {
        cancelBtn.style.background = 'white';
        cancelBtn.style.transform = 'translateY(0)';
    });

    logoutBtn.addEventListener('mouseenter', () => {
        logoutBtn.style.background = '#c0392b';
        logoutBtn.style.transform = 'translateY(-1px)';
    });
    logoutBtn.addEventListener('mouseleave', () => {
        logoutBtn.style.background = '#E74C3C';
        logoutBtn.style.transform = 'translateY(0)';
    });

    // Touch feedback untuk mobile
    [cancelBtn, logoutBtn].forEach(btn => {
        btn.addEventListener('touchstart', () => {
            btn.style.transform = 'scale(0.98)';
        }, { passive: true });
        
        btn.addEventListener('touchend', () => {
            btn.style.transform = 'scale(1)';
        }, { passive: true });
    });

    actionsDiv.appendChild(cancelBtn);
    actionsDiv.appendChild(logoutBtn);

    // Gabungkan semua
    modalContent.appendChild(iconDiv);
    modalContent.appendChild(messageDiv);
    modalContent.appendChild(actionsDiv);
    modalOverlay.appendChild(modalContent);

    // Tambahkan ke body
    document.body.appendChild(modalOverlay);
    document.body.style.overflow = 'hidden';

    // Tambahkan style untuk animasi
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    `;
    document.head.appendChild(style);

    // Close ketika klik di luar
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeLogoutModal();
        }
    });

    // Close dengan ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLogoutModal();
        }
    });
}

// Fungsi untuk close modal
function closeLogoutModal() {
    const modal = document.getElementById('logoutConfirmModal');
    if (modal) {
        modal.style.animation = 'fadeIn 0.2s ease reverse';
        setTimeout(() => {
            if (modal.parentNode) {
                modal.parentNode.removeChild(modal);
            }
            document.body.style.overflow = '';
        }, 200);
    }
}

// ================= UPDATE FUNGSI LOGOUT =================
function confirmLogout(event) {
    event.preventDefault();
    event.stopPropagation();
    showLogoutConfirm();
}

function switchToPenyewa() {
        if (confirm('Apakah Anda yakin ingin beralih ke mode Penyewa?')) {
            fetch("{{ route('switch.to.buyer') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "{{ route('buyer.home') }}";
                } else {
                    alert(data.message || 'Gagal beralih ke mode buyer');
                }
            });
        }
    }
</script>