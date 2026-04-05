{{-- modals/add-schedule.blade.php --}}
<!-- Modal 1: Generate Jadwal Otomatis - FLEKSIBEL -->
<div id="addScheduleModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-plus-circle"></i> Generate Jadwal Otomatis
            </h2>
            <button class="modal-close" onclick="closeAddScheduleModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="generateScheduleForm">
            <input type="hidden" name="section_id" id="generateSectionId">
            <div class="modal-body">
                <!-- Info Pesan -->
                <div class="info-card" style="background: linear-gradient(135deg, #3498db15 0%, #9b59b615 100%); padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #3498db;">
                    <i class="fas fa-info-circle" style="color: #3498db; margin-right: 8px;"></i>
                    <span style="font-size: 13px; color: #2c3e50;">
                        Fleksibel! Tentukan jam operasional sesuai kebutuhan Anda (bisa 24 jam atau malam saja)
                    </span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i> Rentang Tanggal
                    </label>
                    <div style="display: flex; gap: 10px;">
                        <input type="date" id="startDate" name="start_date" class="form-control" style="flex: 1;" required 
                               value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                        <span style="align-self: center;">s/d</span>
                        <input type="date" id="endDate" name="end_date" class="form-control" style="flex: 1;" required
                               value="{{ date('Y-m-d', strtotime('+2 days')) }}" min="{{ date('Y-m-d') }}">
                    </div>
                    <small class="form-hint">Minimal 1 hari, maksimal 30 hari</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-clock"></i> Rentang Jam Harian
                    </label>
                    <div class="time-input-group">
                        <input type="time" id="startTime" name="start_time" class="form-control" required
                               value="09:00">
                        <span>s/d</span>
                        <input type="time" id="endTime" name="end_time" class="form-control" required
                               value="22:00">
                    </div>
                    <small class="form-hint">Contoh: 09:00-22:00 = slot setiap jam</small>
                    
                    <!-- Overnight Info -->
                    <div id="overnightWarning" class="overnight-info" style="display: none;">
                        <i class="fas fa-moon"></i>
                        <span>Slot akan di-generate hingga 23:59, lalu mulai lagi 00:00 keesokan harinya</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-hourglass-half"></i> Durasi per Slot (jam)
                    </label>
                    <select id="rentalDuration" name="rental_duration" class="form-control" required>
                        <option value="1">1 Jam</option>
                        <option value="2" selected>2 Jam</option>
                        <option value="3">3 Jam</option>
                        <option value="4">4 Jam</option>
                        <option value="5">5 Jam</option>
                        <option value="6">6 Jam</option>
                    </select>
                    <small class="form-hint">Durasi setiap slot waktu</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag"></i> Harga Sewa per Slot
                    </label>
                    <div class="price-input-container">
                        <span class="price-prefix">Rp</span>
                        <input type="number" id="rentalPrice" name="rental_price" class="form-control price-input" 
                               min="0" value="100000" required placeholder="Contoh: 100000">
                    </div>
                    <small class="form-hint">Harga untuk setiap slot waktu</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i> Status Awal Slot
                    </label>
                    <select id="availableStatus" name="available" class="form-control" required>
                        <option value="1">Tersedia (Bisa dipesan)</option>
                        <option value="0">Dipesan (Non-aktif)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-week"></i> Hari Aktif
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 10px;">
                        @php
                            $days = [
                                ['value' => 1, 'label' => 'Senin'],
                                ['value' => 2, 'label' => 'Selasa'],
                                ['value' => 3, 'label' => 'Rabu'],
                                ['value' => 4, 'label' => 'Kamis'],
                                ['value' => 5, 'label' => 'Jumat'],
                                ['value' => 6, 'label' => 'Sabtu'],
                                ['value' => 0, 'label' => 'Minggu']
                            ];
                        @endphp
                        @foreach($days as $day)
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px; background: var(--card-bg); border-radius: 8px; transition: all 0.3s;">
                                <input type="checkbox" name="active_days[]" value="{{ $day['value'] }}" 
                                       {{ in_array($day['value'], [1,2,3,4,5]) ? 'checked' : '' }} 
                                       style="cursor: pointer;">
                                <span style="font-size: 14px;">{{ $day['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <small class="form-hint">Slot hanya akan digenerate pada hari yang dipilih</small>
                </div>
                
                <!-- Preview Info -->
                <div id="previewInfo" class="info-card" style="background: linear-gradient(135deg, #A01B4215 0%, #8B153815 100%); padding: 12px; border-radius: 8px; margin-top: 15px; display: none; border-left: 4px solid #8B1538;">
                    <i class="fas fa-calculator" style="color: #8B1538; margin-right: 8px;"></i>
                    <span id="previewText" style="font-size: 13px; color: #2c3e50;">Akan digenerate: X slot</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeAddScheduleModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn-modal btn-modal-primary" onclick="generateSchedule()" id="generateBtn">
                    <i class="fas fa-magic"></i> Generate Jadwal
                </button>
            </div>
        </form>
    </div>
</div>