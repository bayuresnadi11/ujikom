<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       TAB SWITCHING
    =============================== */
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            const tabId = this.dataset.tab;

            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(tabId)?.classList.add('active');
        });
    });


    /* ===============================
       TOGGLE PASSWORD
    =============================== */
    function togglePassword(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        if (!input || !button) return;

        const icon = button.querySelector('i');
        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';
        icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
    }

    document.getElementById('toggleToken')?.addEventListener('click', () =>
        togglePassword('apiToken', 'toggleToken')
    );
    document.getElementById('toggleServerKey')?.addEventListener('click', () =>
        togglePassword('serverKey', 'toggleServerKey')
    );
    document.getElementById('toggleClientKey')?.addEventListener('click', () =>
        togglePassword('clientKey', 'toggleClientKey')
    );


    /* ===============================
       PRODUCTION MODE TOGGLE
    =============================== */
    const productionMode = document.getElementById('productionMode');
    const statusBadge = document.querySelector('.status-badge');

    productionMode?.addEventListener('change', function () {
        if (this.checked) {
            statusBadge.className = 'status-badge success';
            statusBadge.innerHTML = '<span class="status-indicator"></span> Production Mode';
        } else {
            statusBadge.className = 'status-badge warning';
            statusBadge.innerHTML = '<span class="status-indicator"></span> Sandbox Mode';
        }
    });


    /* ===============================
       COPY TO CLIPBOARD
    =============================== */
    window.copyToClipboard = function (button) {
        const input = button.parentElement.querySelector('input');
        if (!input) return;

        input.select();
        document.execCommand('copy');

        const original = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.style.color = 'var(--success)';

        setTimeout(() => {
            button.innerHTML = original;
            button.style.color = '';
        }, 2000);
    };


    /* ===============================
       SAVE & RESET BUTTON
    =============================== */
    const saveBtn = document.getElementById('saveBtn');
    saveBtn?.addEventListener('click', function (e) {
        e.preventDefault();

        const original = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        this.disabled = true;

        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-check"></i> Saved!';
            this.style.background = 'var(--success)';

            setTimeout(() => {
                this.innerHTML = original;
                this.disabled = false;
                this.style.background = '';
            }, 2000);
        }, 1500);
    });

    document.getElementById('resetBtn')?.addEventListener('click', () => {
        if (confirm('Reset semua perubahan?')) location.reload();
    });


    /* ===============================
       LOGO UPLOAD (DRAG & DROP)
    =============================== */
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('logoUpload');
    const previewBox = document.getElementById('newLogoPreview');
    const previewImg = document.getElementById('logoPreviewImg');
    const fileName = document.getElementById('fileName');
    const removeBtn = document.getElementById('removeLogo');

    uploadArea?.querySelector('.browse-text')?.addEventListener('click', e => {
        e.stopPropagation();
        fileInput.click();
    });

    ['dragenter','dragover','dragleave','drop'].forEach(e =>
        uploadArea?.addEventListener(e, ev => {
            ev.preventDefault();
            ev.stopPropagation();
            uploadArea.classList.toggle('dragover', e === 'dragenter' || e === 'dragover');
        })
    );

    uploadArea?.addEventListener('drop', e => {
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    fileInput?.addEventListener('change', function () {
        if (this.files[0]) handleFile(this.files[0]);
    });

    function handleFile(file) {
        const valid = ['image/png','image/jpeg','image/webp'];
        if (!valid.includes(file.type)) return alert('Format gambar tidak valid');
        if (file.size > 2 * 1024 * 1024) return alert('Max 2MB');

        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            fileName.textContent = file.name;
            previewBox.classList.add('show');
        };
        reader.readAsDataURL(file);
    }

    removeBtn?.addEventListener('click', () => {
        fileInput.value = '';
        previewBox.classList.remove('show');
        fileName.textContent = 'No file selected';
    });


    /* ===============================
       SWEETALERT
    =============================== */
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Pengaturan disimpan',
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}'
        });
    @endif


    /* ===============================
       FORM SUBMIT LOADING
    =============================== */
    document.getElementById('settings-form')?.addEventListener('submit', () => {
        Swal.fire({
            title: 'Menyimpan...',
            allowOutsideClick: false,
            didOpen: Swal.showLoading
        });
    });

    requestAnimationFrame(() => {
        document.querySelectorAll('.fade-page, .fade-down').forEach(el => {
            el.classList.add('show');
        });
    });

});
</script>
