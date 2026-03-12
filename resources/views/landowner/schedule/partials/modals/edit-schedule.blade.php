{{-- modals/edit-schedule.blade.php --}}
<!-- Modal 3: Edit Jadwal -->
<div id="editScheduleModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-edit"></i> Edit Jadwal
            </h2>
            <button class="modal-close" onclick="closeEditScheduleModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editScheduleForm">
            <input type="hidden" id="editScheduleId" name="id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i> Tanggal
                    </label>
                    <input type="date" id="editDate" name="date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-clock"></i> Jam Mulai
                    </label>
                    <div class="time-input-group">
                        <input type="time" id="editStartTime" name="start_time" class="form-control" required>
                        <span>s/d</span>
                        <input type="time" id="editEndTime" name="end_time" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag"></i> Harga Sewa
                    </label>
                    <input type="number" id="editRentalPrice" name="rental_price" class="form-control" 
                           min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i> Status
                    </label>
                    <select id="editAvailable" name="available" class="form-control" required>
                        <option value="1">Tersedia</option>
                        <option value="0">Dipesan</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeEditScheduleModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn-modal btn-modal-primary" onclick="updateSchedule()">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>