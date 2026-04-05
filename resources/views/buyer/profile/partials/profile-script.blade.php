<script>
        // Global Variables
        let currentUser = {
            name: "{{ $user->name ?? 'Andi Wijaya' }}",
            phone: "{{ $user->phone ?? '081234567890' }}",
            address: "{{ $user->address ?? 'Jl. SportField No. 123, Jakarta Pusat' }}",
            gender: "{{ $user->gender ?? 'male' }}",
            birthdate: "{{ $user->birthdate ?? '1990-01-15' }}"
        };

        // Untuk menyimpan file avatar yang dipilih
        let selectedAvatarFile = null;

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
        
        // Form handlers
        function handleEditProfile(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('editProfileSubmit');
            const loading = document.getElementById('editProfileLoading');
            const originalText = submitBtn.innerHTML;

            // Get form values
            const name = document.getElementById('editName').value;
            const phone = document.getElementById('editPhone').value;
            const address = document.getElementById('editAddress').value;
            const gender = document.getElementById('editGender').value;
            const birthdate = document.getElementById('editBirthdate').value;

            // Show loading
            submitBtn.innerHTML = 'Menyimpan...';
            loading.style.display = 'inline-block';
            submitBtn.disabled = true;

            // Create FormData untuk mengirim file
            const formData = new FormData();
            formData.append('name', name);
            formData.append('phone', phone);
            formData.append('address', address);
            formData.append('gender', gender);
            formData.append('birthdate', birthdate);
            formData.append('_token', '{{ csrf_token() }}');

            // Tambahkan file avatar jika ada
            if (selectedAvatarFile) {
                formData.append('avatar', selectedAvatarFile);
            }

            // Call real API
            fetch("{{ route('buyer.profile.update') }}", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update user data
                    currentUser = { name, phone, address, gender, birthdate };

                    // Update UI
                    updateProfileUI();

                    // Update avatar preview di main profile jika ada file baru
                    if (selectedAvatarFile) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const mainAvatar = document.getElementById('profileAvatar');
                            mainAvatar.innerHTML = '';
                            mainAvatar.style.backgroundImage = `url(${e.target.result})`;
                            mainAvatar.style.backgroundSize = 'cover';
                            mainAvatar.style.backgroundPosition = 'center';
                        };
                        reader.readAsDataURL(selectedAvatarFile);
                    }

                    showToast('Profil berhasil diperbarui!', 'success');
                    closeModal('editProfileModal');
                } else {
                    showToast(data.message || 'Gagal memperbarui profil', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memperbarui profil', 'error');
            })
            .finally(() => {
                // Reset button
                submitBtn.innerHTML = originalText;
                loading.style.display = 'none';
                submitBtn.disabled = false;

                // Reset file yang dipilih
                selectedAvatarFile = null;
            });
        }
        
        function handleChangePassword(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('changePasswordSubmit');
            const loading = document.getElementById('changePasswordLoading');
            const originalText = submitBtn.innerHTML;

            const oldPassword = document.getElementById('oldPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validation
            if (newPassword.length < 8) {
                showToast('Kata sandi baru minimal 8 karakter', 'error');
                return;
            }

            if (newPassword !== confirmPassword) {
                showToast('Konfirmasi kata sandi tidak cocok', 'error');
                return;
            }

            // Show loading
            submitBtn.innerHTML = 'Mengubah...';
            loading.style.display = 'inline-block';
            submitBtn.disabled = true;

            // Create form data
            const formData = new FormData();
            formData.append('current_password', oldPassword);
            formData.append('new_password', newPassword);
            formData.append('new_password_confirmation', confirmPassword);
            formData.append('_token', '{{ csrf_token() }}');

            // Call real API
            fetch("{{ route('buyer.profile.change-password') }}", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Kata sandi berhasil diubah!', 'success');
                    closeModal('changePasswordModal');

                    // Clear form
                    document.getElementById('oldPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                } else {
                    showToast(data.message || 'Gagal mengubah kata sandi', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat mengubah kata sandi', 'error');
            })
            .finally(() => {
                // Reset button
                submitBtn.innerHTML = originalText;
                loading.style.display = 'none';
                submitBtn.disabled = false;
            });
        }
        
        function updateProfileUI() {
            // Update main profile section
            document.getElementById('profileName').textContent = currentUser.name;
        
            // Update details section
            document.getElementById('detailPhone').textContent = currentUser.phone;
            document.getElementById('detailAddress').textContent = currentUser.address;
            document.getElementById('detailGender').textContent = getGenderText(currentUser.gender);
            document.getElementById('detailBirthdate').textContent = formatDate(currentUser.birthdate);
        }
        
        function getGenderText(gender) {
            switch(gender) {
                case 'male': return 'Laki-laki';
                case 'female': return 'Perempuan';
                default: return 'Lainnya';
            }
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }
        
        // Avatar preview - FUNGSI DISEDERHANAKAN
        function previewAvatar(event) {
            const reader = new FileReader();
            const file = event.target.files[0];
            
            if (!file) return;
            
            reader.onload = function(e) {
                const avatarPreview = document.getElementById('avatarPreviewModal');
                avatarPreview.innerHTML = '';
                avatarPreview.style.backgroundImage = `url(${e.target.result})`;
                avatarPreview.style.backgroundSize = 'cover';
                avatarPreview.style.backgroundPosition = 'center';
                
                // Also update main avatar
                const mainAvatar = document.getElementById('profileAvatar');
                mainAvatar.innerHTML = '';
                mainAvatar.style.backgroundImage = `url(${e.target.result})`;
                mainAvatar.style.backgroundSize = 'cover';
                mainAvatar.style.backgroundPosition = 'center';
            }
            
            reader.readAsDataURL(file);
        }
        
        // Copy to clipboard
        function copyToClipboard(text, label) {
            navigator.clipboard.writeText(text).then(() => {
                showToast(`${label} berhasil disalin ke clipboard`, 'success');
            }).catch(err => {
                showToast('Gagal menyalin ke clipboard', 'error');
            });
        }
        
        
        // Action functions
        function showNotification() {
            showToast('Anda memiliki 3 notifikasi baru', 'info');
        }
        
        function openHelpCenter() {
            showToast('Membuka pusat bantuan', 'info');
            // In real app, redirect to help center
        }
        
        
        function viewAllBookings() {
            showToast('Membuka semua riwayat booking', 'info');
            // In real app, redirect to bookings page
        }
        
        function viewBookingDetail(id) {
            showToast(`Melihat detail booking #${id}`, 'info');
            // In real app, redirect to booking detail page
        }
        
        function rateBooking(id) {
            showToast(`Memberi rating untuk booking #${id}`, 'info');
            // In real app, open rating modal
        }
        
        function cancelBooking(id) {
            if (confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
                showToast(`Booking #${id} berhasil dibatalkan`, 'success');
                // In real app, call cancel booking API
            }
        }
        
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date for birthdate
            const birthdateInput = document.getElementById('editBirthdate');
            const today = new Date();
            const maxDate = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate());
            birthdateInput.max = maxDate.toISOString().split('T')[0];
            
            // Initialize profile data
            updateProfileUI();
        });
        
        // Prevent form submission on demo
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!this.classList.contains('prevent-demo')) {
                    this.classList.add('prevent-demo');
                }
            });
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
        border: 2px solid #e8f5ef;
        background: white;
        color: #0A5C36;
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

function confirmLogout(event) {
    event.preventDefault();
    event.stopPropagation();
    showLogoutConfirm();
}

        // ================= FUNGSI SWITCH =================
        // ================= FUNGSI SWITCH ROLE =================
function confirmSwitchToPemilik() {
    const modal = document.getElementById('confirmSwitchModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeSwitchModal() {
    const modal = document.getElementById('confirmSwitchModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function submitSwitchRequest() {
    // Close modal dulu
    closeSwitchModal();
    
    // Buat form submission
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('switch.to.landowner') }}";

    // CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = "{{ csrf_token() }}";
    form.appendChild(csrfToken);

    // Alasan pengajuan
    const reason = document.createElement('input');
    reason.type = 'hidden';
    reason.name = 'reason';
    reason.value = 'Ingin menjadi pemilik lapangan';
    form.appendChild(reason);

    document.body.appendChild(form);
    form.submit();
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Konfirmasi switch button
    const confirmSwitchBtn = document.getElementById('confirmSwitchBtn');
    if (confirmSwitchBtn) {
        confirmSwitchBtn.addEventListener('click', submitSwitchRequest);
    }
    
    // Close modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSwitchModal();
        }
    });
    
    // Close modal ketika klik di luar
    const switchModal = document.getElementById('confirmSwitchModal');
    if (switchModal) {
        switchModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeSwitchModal();
            }
        });
    }
});
     // Fungsi untuk menampilkan popup sukses
        function showSuccessPopup(message) {
            const popup = document.getElementById('successPopup');
            const messageElement = document.getElementById('successMessage');
            
            messageElement.textContent = message;
            popup.classList.add('active');
            
            // Auto close setelah 5 detik
            setTimeout(() => {
                if (popup.classList.contains('active')) {
                    closeSuccessPopup();
                }
            }, 5000);
        }

        // Fungsi untuk menampilkan popup error
        function showErrorPopup(message) {
            const popup = document.getElementById('errorPopup');
            const messageElement = document.getElementById('errorMessage');
            
            messageElement.textContent = message;
            popup.classList.add('active');
            
            // Auto close setelah 5 detik
            setTimeout(() => {
                if (popup.classList.contains('active')) {
                    closeErrorPopup();
                }
            }, 5000);
        }

        // Fungsi untuk menutup popup sukses
        function closeSuccessPopup() {
            const popup = document.getElementById('successPopup');
            popup.classList.remove('active');
        }

        // Fungsi untuk menutup popup error
        function closeErrorPopup() {
            const popup = document.getElementById('errorPopup');
            popup.classList.remove('active');
        }

        // Cek session messages saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika ada session success
            @if(session('success'))
                showSuccessPopup("{{ session('success') }}");
            @endif

            // Cek jika ada session error
            @if(session('error'))
                showErrorPopup("{{ session('error') }}");
            @endif

            // Close popup dengan tombol ESC
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeSuccessPopup();
                    closeErrorPopup();
                }
            });

            // Close popup dengan klik di luar area popup
            document.addEventListener('click', function(event) {
                const successPopup = document.getElementById('successPopup');
                const errorPopup = document.getElementById('errorPopup');
                
                if (event.target === successPopup) {
                    closeSuccessPopup();
                }
                
                if (event.target === errorPopup) {
                    closeErrorPopup();
                }
            });
        });

        // Fungsi untuk penggunaan dari JavaScript
        window.showSuccessAlert = function(message) {
            showSuccessPopup(message);
        };

        window.showErrorAlert = function(message) {
            showErrorPopup(message);
        };

        function directSwitchToLandowner() {
        if (confirm('Apakah Anda yakin ingin beralih ke mode Pemilik Lapangan?')) {
            // Langsung submit tanpa form
            fetch("{{ route('switch.to.landowner') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    direct_switch: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Gagal beralih ke mode pemilik');
                }
            });
        }
    }

    // Untuk buyer yang belum pernah request
// Fungsi untuk redirect ke halaman pengajuan
function showSwitchModal() {
    window.location.href = "{{ route('buyer.profile.pengajuan') }}";
}

// Fungsi untuk direct switch (sudah pernah diapprove)
function directSwitchToLandowner() {
    if (confirm('Beralih ke Mode Pemilik Lapangan?')) {
        // Buat form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('switch.to.landowner') }}"; // Pakai route baru
        
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
}

// Fungsi popup success dan error tetap sama
function showSuccessPopup(message) {
    const popup = document.getElementById('successPopup');
    const messageElement = document.getElementById('successMessage');
    
    messageElement.textContent = message;
    popup.classList.add('active');
    
    setTimeout(() => {
        if (popup.classList.contains('active')) {
            closeSuccessPopup();
        }
    }, 5000);
}

function showErrorPopup(message) {
    const popup = document.getElementById('errorPopup');
    const messageElement = document.getElementById('errorMessage');
    
    messageElement.textContent = message;
    popup.classList.add('active');
    
    setTimeout(() => {
        if (popup.classList.contains('active')) {
            closeErrorPopup();
        }
    }, 5000);
}

function closeSuccessPopup() {
    const popup = document.getElementById('successPopup');
    popup.classList.remove('active');
}

function closeErrorPopup() {
    const popup = document.getElementById('errorPopup');
    popup.classList.remove('active');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Close popup dengan ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeSuccessPopup();
            closeErrorPopup();
        }
    });

    // Close popup dengan klik di luar
    document.addEventListener('click', function(event) {
        const successPopup = document.getElementById('successPopup');
        const errorPopup = document.getElementById('errorPopup');
        
        if (event.target === successPopup) {
            closeSuccessPopup();
        }
        
        if (event.target === errorPopup) {
            closeErrorPopup();
        }
    });
});
</script>
