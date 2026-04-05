<script>
    function updateCharCounter() {
        const textarea = document.getElementById('reason');
        const charCount = document.getElementById('charCount');
        charCount.textContent = textarea.value.length;
    }

    function showSuccessPopup(message) {
        const popup = document.getElementById('successPopup');
        const messageElement = document.getElementById('successMessage');
        
        messageElement.textContent = message;
        popup.classList.add('active');
        
        setTimeout(() => {
            closeSuccessPopup();
            window.location.href = "{{ route('buyer.profile') }}";
        }, 2000);
    }

    function showErrorPopup(message) {
        const popup = document.getElementById('errorPopup');
        const messageElement = document.getElementById('errorMessage');
        
        messageElement.textContent = message;
        popup.classList.add('active');
    }

    function closeSuccessPopup() {
        document.getElementById('successPopup').classList.remove('active');
    }

    function closeErrorPopup() {
        document.getElementById('errorPopup').classList.remove('active');
    }

    // Check for session messages
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showSuccessPopup("{{ session('success') }}");
        @endif

        @if(session('error'))
            showErrorPopup("{{ session('error') }}");
        @endif

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSuccessPopup();
                closeErrorPopup();
            }
        });

        // Close on overlay click
        document.querySelectorAll('.alert-popup').forEach(popup => {
            popup.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeSuccessPopup();
                    closeErrorPopup();
                }
            });
        });
    });

    // Form submission handling
    document.getElementById('pengajuanForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 6px;"></i>Mengirim...';
    });
</script>