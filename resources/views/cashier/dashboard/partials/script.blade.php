<script>
// Custom Alert & Toast System
class AlertSystem {
    constructor() {
        this.toastContainer = null;
        this.initToastContainer();
    }

    initToastContainer() {
        this.toastContainer = document.createElement('div');
        this.toastContainer.className = 'custom-toast-container';
        document.body.appendChild(this.toastContainer);
    }

    confirm(options = {}) {
        return new Promise((resolve) => {
            const {
                title = 'Konfirmasi',
                message = 'Apakah Anda yakin?',
                confirmText = 'Ya',
                cancelText = 'Batal',
                icon = '<i class="fa-solid fa-triangle-exclamation"></i>'
            } = options;

            const overlay = document.createElement('div');
            overlay.className = 'custom-alert-overlay';
            
            overlay.innerHTML = `
                <div class="custom-alert">
                    <div class="custom-alert-header">
                        <div class="custom-alert-icon">
                            ${icon}
                        </div>
                        <h3 class="custom-alert-title">${title}</h3>
                        <p class="custom-alert-message">${message}</p>
                    </div>
                    <div class="custom-alert-body">
                        <div class="custom-alert-actions">
                            <button type="button" class="custom-alert-btn cancel" data-action="cancel">
                                ${cancelText}
                            </button>
                            <button type="button" class="custom-alert-btn confirm" data-action="confirm">
                                ${confirmText}
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(overlay);
            this.disableScroll();

            const cancelBtn = overlay.querySelector('.cancel');
            const confirmBtn = overlay.querySelector('.confirm');
            const alertBox = overlay.querySelector('.custom-alert');

            cancelBtn.focus();

            const removeOverlay = () => {
                overlay.style.opacity = '0';
                alertBox.style.transform = 'translateY(30px) scale(0.95)';
                
                setTimeout(() => {
                    if (overlay.parentNode) {
                        document.body.removeChild(overlay);
                    }
                    this.enableScroll();
                }, 200);
            };

            const handleAction = (action) => {
                removeOverlay();
                resolve(action === 'confirm');
            };

            cancelBtn.addEventListener('click', () => handleAction('cancel'));
            confirmBtn.addEventListener('click', () => handleAction('confirm'));

            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    handleAction('cancel');
                }
            });

            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    handleAction('cancel');
                }
            };
            document.addEventListener('keydown', handleEscape);

            overlay.addEventListener('animationend', () => {
                document.removeEventListener('keydown', handleEscape);
            }, { once: true });
        });
    }

    toast(message, type = 'success', duration = 4000) {
        const icons = {
            success: 'fa-circle-check',
            error: 'fa-circle-xmark',
            info: 'fa-circle-info',
            warning: 'fa-triangle-exclamation'
        };

        const titles = {
            success: 'Sukses!',
            error: 'Error!',
            info: 'Info',
            warning: 'Peringatan!'
        };

        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        
        toast.innerHTML = `
            <div class="custom-toast-icon">
                <i class="fa-solid ${icons[type]}"></i>
            </div>
            <div class="custom-toast-content">
                <div class="custom-toast-title">${titles[type]}</div>
                <div class="custom-toast-message">${message}</div>
            </div>
            <button class="custom-toast-close" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;

        this.toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 10);

        const closeBtn = toast.querySelector('.custom-toast-close');
        closeBtn.addEventListener('click', () => {
            this.hideToast(toast);
        });

        let timeoutId = setTimeout(() => {
            this.hideToast(toast);
        }, duration);

        toast.addEventListener('mouseenter', () => {
            clearTimeout(timeoutId);
        });

        toast.addEventListener('mouseleave', () => {
            timeoutId = setTimeout(() => {
                this.hideToast(toast);
            }, duration);
        });

        return {
            element: toast,
            hide: () => this.hideToast(toast)
        };
    }

    hideToast(toast) {
        toast.classList.remove('show');
        toast.classList.add('hiding');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 400);
    }

    disableScroll() {
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth + 'px';
    }

    enableScroll() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    showLoading(message = 'Memproses...') {
        const overlay = document.createElement('div');
        overlay.className = 'custom-alert-overlay';
        overlay.style.background = 'rgba(0, 0, 0, 0.7)';
        overlay.style.zIndex = '10000';
        
        overlay.innerHTML = `
            <div class="custom-alert" style="max-width: 280px; text-align: center;">
                <div class="custom-alert-body">
                    <div style="width: 60px; height: 60px; border-radius: 50%; border: 3px solid #f3f3f3; border-top: 3px solid #3498db; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                    <p style="color: #2d3436; font-weight: 500; margin: 0;">${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        this.disableScroll();
        
        return {
            hide: () => {
                if (overlay.parentNode) {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        if (overlay.parentNode) {
                            document.body.removeChild(overlay);
                        }
                        this.enableScroll();
                    }, 200);
                }
            }
        };
    }
}

// Initialize alert system
const alertSystem = new AlertSystem();

// Make alertSystem available globally
window.alertSystem = alertSystem;

// Add spin animation for loading
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Main functionality
document.addEventListener('DOMContentLoaded', function() {
    // ========== SEARCH FUNCTIONALITY ==========
    const searchInput = document.querySelector('input[name="search"]');
    const clearBtn = document.getElementById('clearSearch');
    const searchForm = document.querySelector('form[method="GET"]');
    
    if (searchInput && searchForm) {
        // Show clear button if search value exists
        if (clearBtn) {
            clearBtn.style.display = searchInput.value ? 'block' : 'none';
            
            // Clear search
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                window.location.href = searchForm.action; // Redirect to clear search
            });
        }
        
        // Debounced search (auto-submit after typing stops)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Show/hide clear button
            if (clearBtn) {
                clearBtn.style.display = this.value ? 'block' : 'none';
            }
            
            // Only auto-submit if more than 2 characters or empty
            if (this.value.length === 0 || this.value.length >= 2) {
                searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 500);
            }
        });
        
        // Prevent form submission on Enter if less than 2 characters
        searchForm.addEventListener('submit', function(e) {
            if (searchInput.value.length < 2 && searchInput.value.length > 0) {
                e.preventDefault();
                alertSystem.toast('Masukkan minimal 2 karakter untuk pencarian', 'info');
            }
        });
    }
    
    // ========== DELETE FUNCTIONALITY ==========
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            alertSystem.confirm({
                title: 'Hapus Penyewa',
                message: 'Data penyewa akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
                confirmText: 'Ya, Hapus',
                cancelText: 'Batal',
                icon: '<i class="fa-solid fa-trash-can"></i>'
            }).then((confirmed) => {
                if (confirmed) {
                    const loading = alertSystem.showLoading('Menghapus data...');
                    const row = this.closest('tr');
                    
                    // AJAX delete
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove row with animation
                            row.style.opacity = '0.5';
                            row.style.transform = 'translateX(-20px)';
                            row.style.transition = 'all 0.3s ease';
                            
                            setTimeout(() => {
                                row.remove();
                                
                                // Update row numbers
                                updateRowNumbers();
                                
                                // Update pagination info (but NOT the total count in header)
                                updatePaginationInfo();
                                
                                // Show success toast
                                alertSystem.toast(data.message, 'success');
                                
                                // If no rows left, show empty state or reload
                                if (document.querySelectorAll('tbody tr').length === 0) {
                                    // Reload page to update pagination
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            }, 300);
                        } else {
                            alertSystem.toast(data.message || 'Gagal menghapus data', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alertSystem.toast('Terjadi kesalahan', 'error');
                    })
                    .finally(() => {
                        loading.hide();
                    });
                }
            });
        });
    });
    
    // ========== HELPER FUNCTIONS ==========
    function updateRowNumbers() {
        const rows = document.querySelectorAll('tbody tr');
        const firstItem = parseInt(document.querySelector('tbody tr td.fw-semibold')?.textContent) || 1;
        const startNumber = firstItem - (firstItem - 1);
        
        rows.forEach((row, index) => {
            const numberCell = row.querySelector('td.fw-semibold');
            if (numberCell) {
                numberCell.textContent = startNumber + index;
            }
        });
    }
    
    function updatePaginationInfo() {
        const infoDiv = document.querySelector('.pagination-info');
        if (infoDiv) {
            const currentCount = document.querySelectorAll('tbody tr').length;
            const totalResults = parseInt(infoDiv.textContent.match(/dari (\d+)/)?.[1]) || currentCount;
            const newTotal = Math.max(0, totalResults - 1);
            
            // Update hanya jumlah hasil saat ini, bukan total keseluruhan
            const searchActive = infoDiv.textContent.includes('Total keseluruhan');
            if (searchActive) {
                const overallTotal = infoDiv.textContent.match(/Total keseluruhan: (\d+)/)?.[1];
                infoDiv.textContent = `Menampilkan ${currentCount} dari ${newTotal} hasil (Total keseluruhan: ${overallTotal} penyewa)`;
            } else {
                infoDiv.textContent = `Menampilkan ${currentCount} dari ${newTotal} hasil`;
            }
        }
    }
    
    // ========== HANDLE ADD FORM ==========
    const addForm = document.getElementById('addCustomerForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('#submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading on button
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
            
            // Form will submit normally, session messages will be shown on redirect
        });
    }
    
    // ========== SHOW TOAST FROM SESSION ==========
    @if(session('success'))
        setTimeout(() => {
            alertSystem.toast('{{ session('success') }}', 'success');
        }, 300);
    @endif
    
    @if(session('error'))
        setTimeout(() => {
            alertSystem.toast('{{ session('error') }}', 'error');
        }, 300);
    @endif
});
</script>