{{-- modals/quick-add.blade.php --}}
<!-- Modal 2: Tambah Jadwal Manual -->
<div id="quickAddModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-bolt"></i> Tambah Jadwal Manual
            </h2>
            <button class="modal-close" onclick="closeQuickAddModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="quickAddForm">
            <input type="hidden" name="section_id" id="quickSectionId">
            <div class="modal-body">
                <div class="info-card" style="background: linear-gradient(135deg, #f39c1215 0%, #e67e2215 100%); padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #f39c12;">
                    <i class="fas fa-exclamation-circle" style="color: #f39c12; margin-right: 8px;"></i>
                    <span style="font-size: 13px; color: #2c3e50;">Sistem akan otomatis menghapus jadwal yang sudah lewat</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i> Tanggal
                    </label>
                    <input type="date" id="quickDate" name="date" class="form-control" required
                           value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-clock"></i> Jam Mulai
                    </label>
                    <div class="time-input-group">
                        <input type="time" id="quickStartTime" name="start_time" class="form-control" required
                               value="08:00">
                        <span>s/d</span>
                        <input type="time" id="quickEndTime" name="end_time" class="form-control" required
                               value="10:00">
                    </div>
                    <div class="time-warning" id="quickTimeWarning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Jam selesai kurang dari jam mulai, diasumsikan overnight
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-hourglass-half"></i> Durasi Sewa (jam)
                    </label>
                    <input type="number" id="quickRentalDuration" name="rental_duration" class="form-control" 
                           min="1" max="24" value="2">
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag"></i> Harga Sewa
                    </label>
                    <input type="number" id="quickRentalPrice" name="rental_price" class="form-control" 
                           min="0" value="100000" required>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i> Status
                    </label>
                    <select id="quickAvailable" name="available" class="form-control" required>
                        <option value="1">Tersedia</option>
                        <option value="0">Dipesan</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeQuickAddModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn-modal btn-modal-primary" onclick="quickAddSchedule()">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>