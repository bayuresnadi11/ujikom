{{-- modals/batch-status.blade.php --}}
<!-- Modal 7: Ubah Status Batch -->
<div id="batchStatusModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 450px;">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-toggle-on"></i> Ubah Status Jadwal
            </h2>
            <button class="modal-close" onclick="closeBatchStatusModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="batchStatusForm">
            <div class="modal-body">
                <div class="info-card" style="background: linear-gradient(135deg, #3498db15 0%, #2980b915 100%); padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #3498db;">
                    <i class="fas fa-info-circle" style="color: #3498db; margin-right: 8px;"></i>
                    <span style="font-size: 13px; color: #2c3e50;">
                        Mengubah status <strong><span id="batchCount">0</span> jadwal</strong> yang dipilih
                    </span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i> Status Baru
                    </label>
                    <select id="batchStatus" class="venue-select" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="available">Tersedia (Bisa dipesan)</option>
                        <option value="booked">Dipesan (Non-aktif)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i> Berlaku Untuk
                    </label>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" name="applyType" value="all" checked style="width: 18px; height: 18px;">
                            <span>Semua jadwal yang dipilih</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" name="applyType" value="future" style="width: 18px; height: 18px;">
                            <span>Hanya jadwal yang belum lewat</span>
                        </label>
                    </div>
                </div>
                
                <div id="batchPreview" style="margin-top: 15px; padding: 12px; background: var(--card-bg); border-radius: 8px; display: none;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fas fa-eye"></i>
                        <span style="font-weight: 500;">Preview:</span>
                    </div>
                    <div style="font-size: 13px; color: var(--text-light);">
                        <span id="previewInfoText">Akan mengubah status X jadwal</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeBatchStatusModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn-modal btn-modal-primary" onclick="confirmBatchUpdate()">
                    <i class="fas fa-save"></i> Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Batch Actions -->
<div id="batchActions" class="venue-selector-card" style="display: none; position: fixed; bottom: 80px; left: 20px; right: 20px; z-index: 999; margin: 0; box-shadow: 0 -4px 20px rgba(0,0,0,0.15);">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px;">
        <div class="section-date">
            <i class="fas fa-check-circle"></i>
            <span id="selectedCount">0</span> jadwal dipilih
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button class="header-icon" onclick="deleteSelected()" title="Hapus" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                <i class="fas fa-trash"></i>
            </button>
            <button class="header-icon" onclick="updateStatusSelected()" title="Ubah Status" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                <i class="fas fa-toggle-on"></i>
            </button>
            <button class="header-icon" onclick="clearSelection()" title="Batal">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>