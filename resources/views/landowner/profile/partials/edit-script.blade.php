<script>
    // Preview image before upload
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('avatarPreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const file = input.files[0];
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showToast('Ukuran file maksimal 2MB', 'error');
                input.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showToast('Format file harus JPG, PNG, atau GIF', 'error');
                input.value = '';
                return;
            }
            
            reader.onload = function(e) {
                // Remove icon if exists
                const icon = preview.querySelector('i');
                if (icon) icon.remove();
                
                // Create or update image
                let img = preview.querySelector('img');
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'avatarImage';
                    img.alt = 'Avatar';
                    preview.appendChild(img);
                }
                img.src = e.target.result;
                preview.style.backgroundImage = 'none';
                
                // Add success animation
                preview.style.animation = 'pulse 0.5s';
                setTimeout(() => {
                    preview.style.animation = '';
                }, 500);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Check password strength
    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('passwordStrength');
        let strength = 0;
        
        // Reset classes
        strengthBar.className = 'password-strength-bar';
        strengthBar.style.width = '0%';
        
        if (password.length === 0) {
            return;
        }
        
        // Length check
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        
        // Contains lowercase
        if (/[a-z]/.test(password)) strength++;
        
        // Contains uppercase
        if (/[A-Z]/.test(password)) strength++;
        
        // Contains numbers
        if (/[0-9]/.test(password)) strength++;
        
        // Contains special characters
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Update strength indicator
        if (strength <= 2) {
            strengthBar.classList.add('strength-weak');
        } else if (strength <= 4) {
            strengthBar.classList.add('strength-medium');
        } else {
            strengthBar.classList.add('strength-strong');
        }
        
        // Animate width based on strength
        const width = Math.min(100, (strength / 6) * 100);
        setTimeout(() => {
            strengthBar.style.width = width + '%';
        }, 10);
    }

    // Toggle password visibility
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const submitButton = document.getElementById('submitButton');
        const loading = document.getElementById('loading');
        const submitText = document.getElementById('submitText');
        
        // Show loading
        submitText.style.display = 'none';
        loading.style.display = 'inline-block';
        submitButton.disabled = true;
        
        // Get password values
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        // Validate passwords if any is filled
        if (currentPassword || newPassword || confirmPassword) {
            if (!currentPassword || !newPassword || !confirmPassword) {
                showToast('Harap isi semua field kata sandi jika ingin mengubah', 'error');
                submitText.style.display = 'inline-block';
                loading.style.display = 'none';
                submitButton.disabled = false;
                e.preventDefault();
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showToast('Konfirmasi kata sandi tidak cocok', 'error');
                submitText.style.display = 'inline-block';
                loading.style.display = 'none';
                submitButton.disabled = false;
                e.preventDefault();
                return;
            }
            
            if (newPassword.length < 8) {
                showToast('Kata sandi baru minimal 8 karakter', 'error');
                submitText.style.display = 'inline-block';
                loading.style.display = 'none';
                submitButton.disabled = false;
                e.preventDefault();
                return;
            }
        }
    });

    // Show toast notification
    function showToast(message, type = 'success', duration = 3000) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type}`;
        toast.style.position = 'fixed';
        toast.style.top = '80px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.zIndex = '9999';
        toast.style.minWidth = '300px';
        toast.style.maxWidth = '90%';
        toast.style.boxShadow = 'var(--shadow-lg)';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.getElementById('mobileContainer').appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(-10px)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Show current password strength if any
        const passwordInput = document.getElementById('password');
        if (passwordInput.value) {
            checkPasswordStrength(passwordInput.value);
        }
        
        // Add form validation
        const form = document.getElementById('profileForm');
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.classList.add('is-invalid');
                
                // Scroll to the field
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Show custom error message
                let message = '';
                switch(this.type) {
                    case 'email':
                        message = 'Masukkan email yang valid';
                        break;
                    case 'tel':
                        message = 'Masukkan nomor telepon yang valid';
                        break;
                    default:
                        message = 'Field ini wajib diisi';
                }
                
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = message;
                    this.parentNode.appendChild(errorDiv);
                }
                
                // Show toast for first error
                if (!form.querySelector('.is-invalid')) {
                    showToast('Harap periksa form yang belum lengkap', 'error');
                }
            });
            
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorDiv = this.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            });
        });
        
        // Add click effect to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'LABEL') {
                    this.style.transform = 'scale(0.99)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                }
            });
        });
        
        // Add pulse animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
        
        // Check URL hash for scrolling to password section
        if (window.location.hash === '#password') {
            const passwordCard = document.getElementById('card-password');
            if (passwordCard) {
                setTimeout(() => {
                    passwordCard.scrollIntoView({ behavior: 'smooth' });
                    // Add highlight animation
                    passwordCard.style.animation = 'pulse 1s';
                    setTimeout(() => {
                        passwordCard.style.animation = '';
                    }, 1000);
                }, 100);
            }
        }
    });

    // Function untuk trigger file input
function triggerAvatarUpload() {
    document.getElementById('avatar').click();
}

// Function untuk preview image
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('avatarPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const file = input.files[0];
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran file maksimal 2MB', 'error');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            showToast('Format file harus JPG, PNG, atau GIF', 'error');
            input.value = '';
            return;
        }
        
        reader.onload = function(e) {
            // Remove icon if exists
            const icon = preview.querySelector('i');
            if (icon) icon.remove();
            
            // Create or update image
            let img = preview.querySelector('img');
            if (!img) {
                img = document.createElement('img');
                img.id = 'avatarImage';
                img.alt = 'Avatar';
                preview.appendChild(img);
            }
            img.src = e.target.result;
            preview.style.backgroundImage = 'none';
            
            // Show success message
            showToast('Foto profil berhasil dipilih', 'success');
            
            // Add animation
            preview.style.animation = 'pulse 0.5s';
            setTimeout(() => {
                preview.style.animation = '';
            }, 500);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

    function setActiveNav() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.auto-hide');
    
    alerts.forEach(alert => {
        const duration = parseInt(alert.dataset.duration) || 3000;
        
        setTimeout(() => {
            // Fade out animation
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            alert.style.transition = 'all 0.3s ease';
            
            // Remove from DOM after animation
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300);
        }, duration);
    });
});
function ResendPhone() {
    // Optional: Tambahkan feedback visual sebelum submit
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = `
        <div style="width: 18px; height: 18px; border: 2px solid white; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        Mengirim...
    `;
    btn.disabled = true;
    
    setTimeout(() => {
        document.getElementById('resendPhoneForm').submit();
    }, 500);
}

function showCustomAlert() {
    document.getElementById('customAlert').style.display = 'flex';
}

function hideCustomAlert() {
    document.getElementById('customAlert').style.display = 'none';
}

function confirmCancel() {
    // Submit form ketika user klik Ya
    hideCustomAlert();
    document.getElementById('cancelPhoneForm').submit();
}

// Optional: Tambahkan untuk menutup alert ketika klik di luar
document.getElementById('customAlert').addEventListener('click', function(e) {
    if (e.target === this) {
        hideCustomAlert();
    }
});

</script>