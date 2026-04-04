{{--
    Global Popup Alert Component
    Include this in every layout to enable SweetAlert2 centered popup notifications.

    Usage in controllers:
        return redirect()->back()->with('success', 'Pesan berhasil!');
        return redirect()->back()->with('error', 'Terjadi kesalahan!');
        return redirect()->back()->with('warning', 'Harap perhatikan!');
        return redirect()->back()->with('info', 'Informasi penting.');
--}}

{{-- SweetAlert2 CDN (safe to load multiple times due to deduplication check) --}}
@once
<script>
    if (typeof Swal === 'undefined') {
        const swalScript = document.createElement('script');
        swalScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(swalScript);
    }
</script>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {

    // =============================================
    // GLOBAL SWEETALERT2 CENTERED POPUP SYSTEM
    // =============================================

    const ToastAlert = {

        /**
         * Show a centered popup notification
         * @param {string} message   - Pesan yang ditampilkan
         * @param {string} type      - 'success' | 'error' | 'warning' | 'info'
         * @param {number} timer     - Durasi tampil dalam ms (0 = tidak auto-close)
         */
        show(message, type = 'success', timer = 2500) {
            if (typeof Swal === 'undefined') {
                console.warn('[PopupAlert] SweetAlert2 belum dimuat.');
                return;
            }

            const iconMap = {
                success: 'success',
                error:   'error',
                warning: 'warning',
                info:    'info',
            };

            const titleMap = {
                success: 'Berhasil!',
                error:   'Gagal!',
                warning: 'Perhatian!',
                info:    'Informasi',
            };

            return Swal.fire({
                icon: iconMap[type] || 'info',
                title: titleMap[type] || 'Info',
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: type === 'success' ? '#10b981'
                                  : type === 'error'   ? '#ef4444'
                                  : type === 'warning' ? '#f59e0b'
                                  : '#3b82f6',
                timer: timer > 0 ? timer : undefined,
                timerProgressBar: timer > 0,
                allowOutsideClick: true,
                customClass: {
                    popup:          'swal-popup-center',
                    title:          'swal-popup-title',
                    htmlContainer:  'swal-popup-text',
                    confirmButton:  'swal-popup-btn',
                    timerProgressBar: 'swal-popup-timer',
                },
                showClass: {
                    popup: 'swal-center-show',
                },
                hideClass: {
                    popup: 'swal-center-hide',
                },
            });
        },

        success(message, timer = 2500) { return this.show(message, 'success', timer); },
        error(message, timer = 0)      { return this.show(message, 'error', timer); },
        warning(message, timer = 0)    { return this.show(message, 'warning', timer); },
        info(message, timer = 2500)    { return this.show(message, 'info', timer); },
    };

    // Expose globally
    window.ToastAlert = ToastAlert;

    // =============================================
    // GLOBAL DELETE CONFIRMATION
    // =============================================
    window.confirmDelete = function(title, text) {
        // Support both old string-call and new object-call signatures
        let config = { title: 'Hapus Data?', text: 'Data yang dihapus tidak dapat dikembalikan!' };
        if (typeof title === 'string') config.title = title;
        if (typeof text  === 'string') config.text  = text;
        if (typeof title === 'object' && title !== null) config = Object.assign(config, title);

        return Swal.fire({
            icon: 'warning',
            title: config.title,
            text: config.text,
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: config.confirmButtonText || 'Ya, Hapus!',
            cancelButtonText: config.cancelButtonText  || 'Batal',
            reverseButtons: true,
            customClass: {
                popup:         'swal-popup-center',
                title:         'swal-popup-title',
                htmlContainer: 'swal-popup-text',
            },
        });
    };

    // Legacy alias (backward compatibility)
    window.showSuccessPopup = function(message) { ToastAlert.success(message); };
    window.showErrorPopup   = function(message) { ToastAlert.error(message); };
    window.showWarningPopup = function(message) { ToastAlert.warning(message); };
    window.showInfoPopup    = function(message) { ToastAlert.info(message); };

    // =============================================
    // AUTO-TRIGGER FROM LARAVEL SESSION FLASH
    // =============================================
    @if(session('success'))
        ToastAlert.success(@json(session('success')));
    @endif

    @if(session('error'))
        ToastAlert.error(@json(session('error')));
    @endif

    @if(session('warning'))
        ToastAlert.warning(@json(session('warning')));
    @endif

    @if(session('info'))
        ToastAlert.info(@json(session('info')));
    @endif

});
</script>

{{-- Custom CSS untuk centered popup --}}
@once
<style>
    /* ===== Centered Popup Styling ===== */
    .swal-popup-center {
        border-radius: 20px !important;
        padding: 32px 28px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        max-width: 340px !important;
        width: 90% !important;
        font-family: inherit !important;
    }

    .swal-popup-title {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        margin-top: 8px !important;
        margin-bottom: 4px !important;
    }

    .swal-popup-text {
        font-size: 0.9rem !important;
        color: #6b7280 !important;
        line-height: 1.5 !important;
    }

    .swal-popup-btn {
        border-radius: 10px !important;
        padding: 10px 32px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        box-shadow: none !important;
        transition: all 0.2s ease !important;
    }

    .swal-popup-btn:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }

    .swal-popup-timer {
        border-radius: 0 0 20px 20px !important;
    }

    /* ===== Show / Hide Animations ===== */
    .swal-center-show {
        animation: swalCenterIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }

    .swal-center-hide {
        animation: swalCenterOut 0.2s ease-in !important;
    }

    @keyframes swalCenterIn {
        0%   { opacity: 0; transform: scale(0.8) translateY(-20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    @keyframes swalCenterOut {
        0%   { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0.9); }
    }

    /* ===== SweetAlert2 Icon Size Adjustment ===== */
    .swal-popup-center .swal2-icon {
        width: 60px !important;
        height: 60px !important;
        margin: 0 auto 16px !important;
    }

    .swal-popup-center .swal2-icon .swal2-icon-content {
        font-size: 2rem !important;
    }

    /* ===== Overlay ===== */
    .swal2-backdrop-show {
        background: rgba(0, 0, 0, 0.45) !important;
        backdrop-filter: blur(2px) !important;
    }

    /* ===== Actions area ===== */
    .swal-popup-center .swal2-actions {
        margin-top: 20px !important;
    }

    /* ===== Cancel button (for confirmDelete) ===== */
    .swal-popup-center .swal2-cancel {
        border-radius: 10px !important;
        padding: 10px 24px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
    }
</style>
@endonce
