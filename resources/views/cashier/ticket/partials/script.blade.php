
<script>
function showQr(ticketCode) {
    document.getElementById('qrContainer').innerHTML = '';
    document.getElementById('ticketCode').innerText = ticketCode;

    new QRCode(document.getElementById('qrContainer'), {
        text: ticketCode,
        width: 220,
        height: 220
    });

    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const logoutModal = document.getElementById('logoutModal');
    
    if (logoutModal) {
        // Tambahkan event listener untuk animasi
        logoutModal.addEventListener('show.bs.modal', function () {
            console.log('Logout modal akan ditampilkan');
        });
        
        logoutModal.addEventListener('hidden.bs.modal', function () {
            console.log('Logout modal ditutup');
        });
    }
    
    // Optional: Auto focus pada tombol Batal saat modal terbuka
    const modalInstance = new bootstrap.Modal(logoutModal);
    logoutModal.addEventListener('shown.bs.modal', function () {
        const cancelBtn = this.querySelector('[data-bs-dismiss="modal"]');
        if (cancelBtn) cancelBtn.focus();
    });
});
</script>