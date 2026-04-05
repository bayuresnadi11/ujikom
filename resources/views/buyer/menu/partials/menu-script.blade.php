<script>
        // Fungsi untuk menampilkan toast notification
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            // Set warna berdasarkan type
            const colors = {
                'info': 'var(--gradient-primary)',
                'warning': 'linear-gradient(135deg, #FF9800 0%, #FF5722 100%)',
                'success': 'linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%)'
            };
            
            toast.style.background = colors[type] || colors.info;
            toastMessage.textContent = message;
            toast.style.display = 'flex';
            
            // Animasi masuk
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            // Sembunyikan setelah 3 detik
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 3000);
        }

        // Fungsi untuk meminta login
        function requireLogin(feature) {
            const featureNames = {
                'booking': 'Booking Lapangan',
                'sparring': 'Cari Sparring',
                'main-bareng': 'Main Bareng',
                'community': 'Komunitas',
                'events': 'Event Olahraga',
                'equipment': 'Sewa Alat',
                'trainer': 'Cari Pelatih'
            };
            
            showToast(
                `Silakan login untuk mengakses fitur ${featureNames[feature] || feature}`,
                'warning'
            );
            
            // Bisa ditambahkan redirect ke login setelah beberapa detik
            setTimeout(() => {
                if (confirm(`Anda perlu login untuk mengakses fitur ini. Ingin login sekarang?`)) {
                    redirectToLogin();
                }
            }, 1000);
        }

        function redirectToRegister() {
            showToast('Mengarahkan ke halaman pendaftaran...', 'success');
            setTimeout(() => {
                alert('Halaman Pendaftaran - Demo Mode\n\nSilakan isi data diri untuk membuat akun baru.');
            }, 500);
        }
        // Toast notification function
        function showToast(message, type = 'info') {
            alert(message); // Temporary using alert
        }
        
        // Redirect to venue register
        function redirectToVenueRegister() {
            window.location.href = '{{ route("register") }}?role=pemilik';
        }

        // Fungsi navigasi tab
        function switchTab(tab) {
            // Hapus kelas active dari semua tab
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Tambahkan kelas active ke tab yang diklik
            event.target.closest('.nav-item').classList.add('active');
            
            // Navigasi ke tab yang dipilih
            if (tab !== 'menu') {
                showToast(`Halaman ${tab} akan segera tersedia!`, 'info');
                
                // Simulasi loading
                setTimeout(() => {
                    if (tab === 'home') {
                        window.location.href = '/'; // Kembali ke halaman utama
                    } else {
                        alert(`Halaman ${tab.charAt(0).toUpperCase() + tab.slice(1)} - Dalam Pengembangan\n\nFitur ini masih dalam tahap pengembangan.`);
                    }
                }, 500);
            }
        }

        // Fungsi lainnya
        function showNotification() {
            requireLogin('notifikasi');
        }

        // Inisialisasi event listener untuk menu cards
        document.addEventListener('DOMContentLoaded', function() {
            const menuCards = document.querySelectorAll('.menu-card:not(.locked)');
            menuCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Locked cards memiliki efek berbeda
            const lockedCards = document.querySelectorAll('.menu-card.locked');
            lockedCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 300);
                });
            });
        });
    </script>