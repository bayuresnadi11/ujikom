{{-- modals/delete-confirm.blade.php --}}
<!-- Modal 4: Konfirmasi Hapus -->
<div id="deleteConfirmModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
            </h2>
            <button class="modal-close" onclick="closeDeleteConfirmModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; margin-bottom: 20px;">
                <i class="fas fa-trash-alt" style="font-size: 64px; color: #e74c3c;"></i>
            </div>
            <h3 style="text-align: center; margin-bottom: 10px; font-size: 18px;">Hapus Jadwal?</h3>
            <p style="text-align: center; color: var(--text-light); margin-bottom: 20px; font-size: 14px;">
                Apakah Anda yakin ingin menghapus <strong><span id="deleteCount">1</span> jadwal</strong>?
            </p>
            <p style="text-align: center; color: #e74c3c; font-size: 13px;">
                <i class="fas fa-exclamation-circle"></i> Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal btn-modal-secondary" onclick="closeDeleteConfirmModal()">
                <i class="fas fa-times"></i> Batal
            </button>
            <button type="button" class="btn-modal btn-modal-primary" onclick="confirmDelete()" 
                    style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                <i class="fas fa-trash"></i> Ya, Hapus
            </button>
        </div>
    </div>
</div>