<script>
// File ini berisi JavaScript untuk Index Schedule (Filtering & Listing)
// Global Variables
let currentSectionId = null;
let currentSectionName = '';
let currentVenueName = '';
let selectedSchedules = [];
let currentDateFilter = '{{ date("Y-m-d") }}'; // Default ke hari ini
let autoRefreshInterval = null;
let isAutoRefresh = false;

// Initialize
// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Schedule page initialized');
    
    // Set default date filter ke hari ini
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('dateFilter');
    if(dateInput) {
        if(!dateInput.value) dateInput.value = today;
        
        // Add event listeners
        dateInput.addEventListener('change', function() {
            updateDateDisplay();
            loadSchedules();
        });
    }
    
    updateDateDisplay();
    
    // Initial load if section selected (e.g. from back button or query param)
    // Check URL params first
    const urlParams = new URLSearchParams(window.location.search);
    const venueId = urlParams.get('venue_id');
    const sectionId = urlParams.get('section_id');

    const venueSelect = document.getElementById('fieldFilter');
    
    // If venue_id is in URL, ensure it is selected (though Blade might have done it)
    if (venueId && venueSelect) {
        venueSelect.value = venueId;
        // Load sections and then select section if present
        loadSections(venueId).then(() => {
            if (sectionId) {
                 const sectionSelect = document.getElementById('sectionFilter');
                 if (sectionSelect) {
                     sectionSelect.value = sectionId;
                     // Trigger change event or load schedules directly
                     loadSchedules();
                 }
            }
        });
    } else {
        // Fallback or just normal load
        const sectionSelect = document.getElementById('sectionFilter');
        if (sectionSelect && sectionSelect.value) {
            loadSchedules();
        }
    }
});

// Toast Function
function showToast(message, type = 'info') {
    // Simple alert for now, or use existing toast if partial exists in layout
    // Assuming toast container exists in layout or index
    const toast = document.getElementById('toast');
    if(!toast) return;

    const iconClass = type === 'success' ? 'fa-check-circle' : 
                     type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    toast.innerHTML = `
        <i class="fas ${iconClass}"></i>
        <span>${message}</span>
    `;
    
    toast.style.display = 'flex';
    toast.style.animation = 'slideInRight 0.3s ease-out';
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 300);
    }, 3000);
}

// Load Sections by Venue
function loadSections(venueId) {
    const sectionFilter = document.getElementById('sectionFilter');
    
    if (!venueId) {
        sectionFilter.innerHTML = '<option value="">-- Pilih Lapangan --</option>';
        sectionFilter.disabled = true;
        document.getElementById('scheduleContainer').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
        return Promise.resolve(); // Return resolved promise
    }
    
    sectionFilter.innerHTML = '<option value="">Memuat sections...</option>';
    sectionFilter.disabled = true;
    
    currentSectionId = null;
    
    document.getElementById('scheduleContainer').style.display = 'none';
    document.getElementById('emptyState').style.display = 'block';
    
    return fetch(`/landowner/schedule/sections/${venueId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.sections && data.sections.length > 0) {
                sectionFilter.innerHTML = '<option value="">-- Pilih Lapangan --</option>';
                data.sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.section_name;
                    sectionFilter.appendChild(option);
                });
                sectionFilter.disabled = false;
            } else {
                sectionFilter.innerHTML = '<option value="">-- Tidak ada lapangan --</option>';
                showToast('Tidak ada lapangan untuk venue ini', 'warning');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            sectionFilter.innerHTML = '<option value="">-- Error memuat lapangan --</option>';
            showToast('Gagal memuat lapangan', 'error');
        });
}

function loadSchedules() {
    const sectionEl = document.getElementById('sectionFilter');
    const dateEl = document.getElementById('dateFilter');
    const scheduleContainer = document.getElementById('scheduleContainer');
    const emptyState = document.getElementById('emptyState');
    const scheduleList = document.getElementById('scheduleList');

    // ⛔ Element penting belum siap → stop, jangan lempar error
    if (!sectionEl || !dateEl || !scheduleContainer || !emptyState || !scheduleList) {
        console.warn('loadSchedules dibatalkan: element belum lengkap');
        return;
    }

    const sectionId = sectionEl.value;
    const dateFilter = dateEl.value;

    // ⛔ Jika belum pilih section
    if (!sectionId) {
        scheduleContainer.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    // ✅ Tampilkan container
    emptyState.style.display = 'none';
    scheduleContainer.style.display = 'block';

    // Loading state
    scheduleList.innerHTML = `
        <div class="empty-state">
            <i class="fas fa-spinner fa-spin"></i> Loading...
        </div>
    `;

    // Simpan current section (kalau dipakai global)
    window.currentSectionId = sectionId;

    const url = `/landowner/schedule/jadwal/${sectionId}?date=${dateFilter}`;

    fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Response tidak OK');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && Array.isArray(data.schedules)) {
                if (data.schedules.length > 0) {
                    renderSchedules(data.schedules);
                } else {
                    scheduleList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-calendar-times empty-state-icon"></i>
                            <h3 class="empty-state-title">Tidak Ada Jadwal</h3>
                            <p class="empty-state-desc">Belum ada jadwal untuk tanggal ini.</p>
                        </div>
                    `;
                }
            } else {
                scheduleList.innerHTML = `
                    <div class="empty-state">
                        Gagal memuat jadwal.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('loadSchedules error:', error);
            scheduleList.innerHTML = `
                <div class="empty-state">
                    Terjadi kesalahan koneksi.
                </div>
            `;
        });
}


// Render Schedules
function renderSchedules(schedules) {
    const scheduleList = document.getElementById('scheduleList');
    const now = new Date();
    
    let html = '';
    
    schedules.forEach(schedule => {
        const date = new Date(schedule.date);
        const scheduleEnd = new Date(`${schedule.date}T${schedule.end_time}`);
        const isExpired = scheduleEnd < now;
        
        let statusClass = 'mini-badge';
        let statusStyle = '';
        let statusText = '';
        
        if (isExpired) {
            statusStyle = 'background: #95a5a6; color: white;';
            statusText = 'Sudah Lewat';
        } else if (schedule.available) {
            statusStyle = 'background: #8B1538; color: white;';
            statusText = 'Tersedia';
        } else {
            statusStyle = 'background: #e74c3c; color: white;';
            statusText = 'Dipesan';
        }
        
        const price = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(schedule.rental_price);
        
        html += `
            <div class="section-card ${isExpired ? 'expired-schedule' : ''}" 
                 onclick="window.location.href='{{ url('landowner/schedule') }}/${schedule.id}/edit'"
                 style="cursor: pointer; opacity: ${isExpired ? '0.7' : '1'};">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="schedule-time-display">
                            <span class="time-range" style="font-weight:800; font-size:16px;">${schedule.start_time.substring(0,5)} - ${schedule.end_time.substring(0,5)}</span>
                            <span class="duration-badge" style="font-size:12px; background:#eee; padding:2px 6px; border-radius:4px; margin-left:8px;">${schedule.rental_duration || '1'} jam</span>
                        </div>
                        <div class="section-venue" style="margin-top:4px;">${date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}</div>
                    </div>
                </div>
                
                <div class="section-content">
                    <div class="section-badges">
                        <span class="${statusClass}" style="${statusStyle}">
                            <i class="fas ${isExpired ? 'fa-clock' : schedule.available ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                            ${statusText}
                        </span>
                        <span class="mini-badge">
                            <i class="fas fa-money-bill-wave"></i>
                            ${price}
                        </span>
                    </div>
                </div>
                
                <div class="section-footer">
                     <span style="font-size:12px; color:#666;">Klik card untuk edit</span>
                     <button type="button" class="card-action-btn btn-delete-card" style="width:auto; padding:0 10px;" onclick="event.stopPropagation(); confirmDeleteSingle(${schedule.id})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    });
    
    scheduleList.innerHTML = html;
}

function updateDateDisplay() {
    const startStr = document.getElementById('dateFilter').value;
    const display = document.getElementById('currentDateDisplay');
    if (!display) return;
    
    if (startStr) {
        const date = new Date(startStr);
        display.textContent = date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    } else {
        display.textContent = 'Pilih Tanggal';
    }
}

function searchSchedule() {
    // Basic search implementation (client-side filter usually, but here we depend on server constraints)
    // For now simple alert or just console log as real implementation would require backend search
    console.log('Search functionality to be implemented or rely on list filtering');
}

// Edit Schedule
function editSchedule(scheduleId) {
    const dateFilter = document.getElementById('dateFilter').value || '{{ date("Y-m-d") }}';
    
    fetch(`/landowner/schedule/jadwal/${currentSectionId}?date=${dateFilter}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const schedule = data.schedules.find(s => s.id == scheduleId);
                if (schedule) {
                    document.getElementById('editScheduleId').value = schedule.id;
                    document.getElementById('editDate').value = schedule.date;
                    document.getElementById('editStartTime').value = schedule.start_time;
                    document.getElementById('editEndTime').value = schedule.end_time;
                    document.getElementById('editRentalPrice').value = schedule.rental_price;
                    document.getElementById('editAvailable').value = schedule.available ? '1' : '0';
                    
                    // Check if schedule is expired
                    const now = new Date();
                    const scheduleEnd = new Date(`${schedule.date}T${schedule.end_time}`);
                    const isExpired = scheduleEnd < now;
                    
                    if (isExpired) {
                        showToast('Jadwal sudah lewat, hanya bisa dilihat', 'warning');
                    }
                    
                    showEditScheduleModal();
                }
            }
        });
}

// Update Schedule
function updateSchedule() {
    const scheduleId = document.getElementById('editScheduleId').value;
    const form = document.getElementById('editScheduleForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Check if schedule is in the past
    const now = new Date();
    const scheduleEnd = new Date(`${data.date}T${data.end_time}`);
    if (scheduleEnd < now) {
        showToast('Tidak dapat mengupdate jadwal yang sudah lewat', 'error');
        return;
    }
    
    if (data.start_time >= data.end_time) {
        showToast('Jam selesai harus setelah jam mulai', 'error');
        return;
    }
    
    // Show loading
    showLoading('Memperbarui Jadwal', 'Menyimpan perubahan...');
    
    fetch(`/landowner/schedule/update/${scheduleId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast('✅ Jadwal berhasil diperbarui', 'success');
            closeEditScheduleModal();
            
            // Animate the updated card
            const updatedCard = document.querySelector(`.section-card[data-id="${scheduleId}"]`);
            if (updatedCard) {
                updatedCard.style.animation = 'fadeIn 0.5s ease-out';
                setTimeout(() => {
                    updatedCard.style.animation = '';
                }, 500);
            }
            
            // Reload schedules
            loadSchedules();
        } else {
            showToast(data.message || '❌ Gagal update jadwal', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('❌ Terjadi kesalahan', 'error');
    });
}

// Selection Functions
function toggleScheduleSelection(scheduleId) {
    const index = selectedSchedules.indexOf(scheduleId);
    if (index === -1) {
        selectedSchedules.push(scheduleId);
    } else {
        selectedSchedules.splice(index, 1);
    }
    updateSelectionUI();
}

function updateSelectionUI() {
    const batchActions = document.getElementById('batchActions');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCount.textContent = selectedSchedules.length;
    
    if (selectedSchedules.length > 0) {
        batchActions.style.display = 'block';
        document.querySelectorAll('.section-card[data-id]').forEach(item => {
            const id = parseInt(item.dataset.id);
            if (selectedSchedules.includes(id)) {
                item.classList.add('selected-card');
                item.style.border = '2px solid var(--primary)';
                item.style.boxShadow = '0 4px 15px rgba(52, 152, 219, 0.3)';
                const checkbox = item.querySelector('.schedule-checkbox');
                if (checkbox) checkbox.checked = true;
            } else {
                item.classList.remove('selected-card');
                item.style.border = '';
                item.style.boxShadow = '';
                const checkbox = item.querySelector('.schedule-checkbox');
                if (checkbox) checkbox.checked = false;
            }
        });
    } else {
        batchActions.style.display = 'none';
        document.querySelectorAll('.section-card[data-id]').forEach(item => {
            item.classList.remove('selected-card');
            item.style.border = '';
            item.style.boxShadow = '';
        });
    }
}

function clearSelection() {
    selectedSchedules = [];
    updateSelectionUI();
}

// Delete Functions
function confirmDeleteSingle(scheduleId) {
    selectedSchedules = [scheduleId];
    
    Swal.fire({
        title: 'Hapus Jadwal?',
        text: "Apakah Anda yakin ingin menghapus jadwal ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            performDelete();
        }
    });
}

function deleteSelected() {
    if (selectedSchedules.length === 0) {
        showToast('Pilih jadwal terlebih dahulu', 'error');
        return;
    }
    
    // Check if any selected schedule is expired
    const now = new Date();
    let hasExpired = false;
    document.querySelectorAll('.section-card[data-id]').forEach(item => {
        const id = parseInt(item.dataset.id);
        if (selectedSchedules.includes(id)) {
            const isExpired = item.dataset.expired === 'true';
            if (isExpired) {
                hasExpired = true;
            }
        }
    });
    
    if (hasExpired) {
        showToast('Jadwal yang sudah lewat tidak bisa dihapus', 'warning');
        return;
    }
    
    Swal.fire({
        title: `Hapus ${selectedSchedules.length} Jadwal?`,
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            performDelete();
        }
    });
}

function performDelete() {
    if (!Array.isArray(selectedSchedules) || selectedSchedules.length === 0) return;

    Swal.fire({
        title: 'Menghapus...',
        text: 'Mohon tunggu',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const isSingle = selectedSchedules.length === 1;
    const url = isSingle
        ? `/landowner/schedule/delete/${selectedSchedules[0]}`
        : `/landowner/schedule/delete-multiple`;

    const options = {
        method: isSingle ? 'DELETE' : 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    };

    if (!isSingle) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify({ ids: selectedSchedules });
    }

    fetch(url, options)
        .then(response => {
            if (!response.ok) {
                throw new Error('Response not OK');
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                Swal.fire('Gagal!', data.message || 'Gagal menghapus.', 'error');
                return;
            }

            Swal.fire(
                'Terhapus!',
                isSingle
                    ? 'Jadwal berhasil dihapus.'
                    : `${data.deleted} jadwal berhasil dihapus.`,
                'success'
            );

            // 🔐 AMANKAN UI UPDATE
            try {
                loadSchedules();
                clearSelection();
            } catch (e) {
                console.warn('UI update error:', e);
            }
        })
        .catch(err => {
            console.error('Delete error:', err);
            Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
        });
}


// Auto-refresh functions
function toggleAutoRefresh() {
    const btn = document.getElementById('autoRefreshBtn');
    const infoDiv = document.getElementById('autoRefreshInfo');
    
    if (isAutoRefresh) {
        // Turn off auto-refresh
        clearInterval(autoRefreshInterval);
        isAutoRefresh = false;
        btn.innerHTML = '<i class="fas fa-redo"></i>';
        btn.style.color = '';
        infoDiv.style.display = 'none';
        showToast('Auto-refresh dimatikan', 'info');
    } else {
        // Turn on auto-refresh
        isAutoRefresh = true;
        btn.innerHTML = '<i class="fas fa-pause"></i>';
        btn.style.color = '#8B1538';
        infoDiv.style.display = 'block';
        showToast('Auto-refresh aktif (30 detik)', 'success');
        
        // Start auto-refresh interval
        autoRefreshInterval = setInterval(() => {
            if (currentSectionId) {
                console.log('Auto-refreshing schedules...');
                loadSchedules();
            }
        }, 30000); // 30 seconds
    }
}

// Check for expired schedules
function checkForExpiredSchedules() {
    // Check every minute
    setInterval(() => {
        const now = new Date();
        const expiredCards = document.querySelectorAll('.section-card[data-expired="false"]');
        
        expiredCards.forEach(card => {
            const date = card.querySelector('.section-venue').textContent;
            const timeRange = card.querySelector('.time-range').textContent;
            const endTime = timeRange.split(' - ')[1];
            
            // Parse date and time
            const dateParts = date.split(' ');
            const day = dateParts[1];
            const month = dateParts[3];
            const year = dateParts[5];
            const monthMap = {
                'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
                'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
                'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
            };
            
            const dateStr = `${year}-${monthMap[month]}-${day.padStart(2, '0')}`;
            const scheduleEnd = new Date(`${dateStr}T${endTime}`);
            
            if (scheduleEnd < now) {
                // Mark as expired
                card.dataset.expired = 'true';
                card.classList.add('expired-schedule');
                
                // Update status badge
                const statusBadge = card.querySelector('.mini-badge');
                if (statusBadge) {
                    statusBadge.style.background = 'linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%)';
                    statusBadge.innerHTML = '<i class="fas fa-clock"></i> Sudah Lewat';
                }
                
                // Add warning text if not already present
                if (!card.querySelector('.time-warning')) {
                    const warningDiv = document.createElement('div');
                    warningDiv.className = 'time-warning';
                    warningDiv.innerHTML = '<i class="fas fa-clock"></i> Sudah lewat';
                    card.querySelector('.section-title-wrapper').appendChild(warningDiv);
                }
                
                // Remove edit button
                const editBtn = card.querySelector('.btn-edit-card');
                if (editBtn) {
                    editBtn.style.display = 'none';
                }
            }
        });
    }, 60000); // Check every minute
}

// Enhanced filterSchedule function
function filterSchedule() {
    const status = document.getElementById('filterStatus').value;
    const cards = document.querySelectorAll('.section-card[data-id]');
    let visibleCount = 0;
    
    // Add transition effect
    cards.forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        card.style.opacity = '0.5';
        card.style.transform = 'scale(0.98)';
    });
    
    setTimeout(() => {
        cards.forEach(card => {
            if (!status) {
                card.style.display = '';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
                visibleCount++;
                return;
            }
            
            const isExpired = card.dataset.expired === 'true';
            const isAvailable = card.dataset.available === 'true';
            
            let shouldShow = false;
            switch (status) {
                case 'available':
                    shouldShow = !isExpired && isAvailable;
                    break;
                case 'booked':
                    shouldShow = !isExpired && !isAvailable;
                    break;
                case 'expired':
                    shouldShow = isExpired;
                    break;
            }
            
            if (shouldShow) {
                card.style.display = '';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show filter result message
        if (status) {
            const statusText = document.querySelector(`#filterStatus option[value="${status}"]`).textContent;
            showToast(`Menampilkan ${visibleCount} jadwal (${statusText})`, 'info');
        }
    }, 150);
}

// Get section statistics
function getSectionStats() {
    if (!currentSectionId) {
        showToast('Pilih lapangan terlebih dahulu', 'warning');
        return;
    }
    
    fetch(`/landowner/schedule/stats/${currentSectionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.stats;
                const statsContent = `
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
                        <div style="background: linear-gradient(135deg, #3498db15 0%, #2980b915 100%); padding: 15px; border-radius: 10px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #3498db;">${stats.total}</div>
                            <div style="font-size: 12px; color: #666;">Total Jadwal</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #A01B4215 0%, #8B153815 100%); padding: 15px; border-radius: 10px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #8B1538;">${stats.today}</div>
                            <div style="font-size: 12px; color: #666;">Hari Ini</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #2ecc7115 0%, #27ae6015 100%); padding: 15px; border-radius: 10px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #2ecc71;">${stats.available}</div>
                            <div style="font-size: 12px; color: #666;">Tersedia</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #e74c3c15 0%, #c0392b15 100%); padding: 15px; border-radius: 10px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #e74c3c;">${stats.booked}</div>
                            <div style="font-size: 12px; color: #666;">Dipesan</div>
                        </div>
                    </div>
                    <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 15px; border-radius: 10px;">
                        <h4 style="margin-top: 0; color: #2c3e50;">Statistik Real-time</h4>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Total Slot:</span>
                            <strong>${stats.total}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Slot Hari Ini:</span>
                            <strong>${stats.today}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Tersedia:</span>
                            <strong style="color: #8B1538;">${stats.available}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Dipesan:</span>
                            <strong style="color: #e74c3c;">${stats.booked}</strong>
                        </div>
                    </div>
                `;
                
                document.getElementById('statsContent').innerHTML = statsContent;
                document.getElementById('statsModal').style.display = 'flex';
            }
        });
}

function closeStatsModal() {
    document.getElementById('statsModal').style.display = 'none';
}

// Modal Functions
function showAddScheduleModal() {
    if (!currentSectionId) {
        showToast('Pilih lapangan terlebih dahulu', 'error');
        return;
    }
    document.getElementById('addScheduleModal').style.display = 'flex';
}

function closeAddScheduleModal() {
    document.getElementById('addScheduleModal').style.display = 'none';
}

function showQuickAddModal() {
    if (!currentSectionId) {
        showToast('Pilih lapangan terlebih dahulu', 'error');
        return;
    }
    document.getElementById('quickAddModal').style.display = 'flex';
}

function closeQuickAddModal() {
    document.getElementById('quickAddModal').style.display = 'none';
}

function showEditScheduleModal() {
    document.getElementById('editScheduleModal').style.display = 'flex';
}

function closeEditScheduleModal() {
    document.getElementById('editScheduleModal').style.display = 'none';
}

// Utility Functions
function searchSchedule() {
    const searchTerm = document.getElementById('searchSchedule').value.toLowerCase();
    document.querySelectorAll('.section-card[data-id]').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

function exportSchedule() {
    showToast('Fitur export dalam pengembangan', 'info');
}

function refreshData() {
    if (currentSectionId) {
        loadSchedules();
        showToast('Data diperbarui', 'success');
    } else {
        showToast('Pilih lapangan terlebih dahulu', 'warning');
    }
}

// ============================================
// BATCH STATUS FUNCTIONS
// ============================================

// Batch Status Functions
function updateStatusSelected() {
    if (selectedSchedules.length === 0) {
        showToast('Pilih jadwal terlebih dahulu', 'error');
        return;
    }
    
    // Update count in modal
    document.getElementById('batchCount').textContent = selectedSchedules.length;
    
    // Reset form
    document.getElementById('batchStatus').value = '';
    document.getElementById('batchStatus').focus();
    
    // Show preview
    updateBatchPreview();
    
    // Show modal
    document.getElementById('batchStatusModal').style.display = 'flex';
}

function updateBatchPreview() {
    const batchCount = selectedSchedules.length;
    const status = document.getElementById('batchStatus').value;
    const applyType = document.querySelector('input[name="applyType"]:checked').value;
    
    const previewDiv = document.getElementById('batchPreview');
    const previewText = document.getElementById('previewInfoText');
    
    if (status) {
        let statusText = status === 'available' ? 'Tersedia' : 'Dipesan';
        let applyText = applyType === 'all' ? 'semua' : 'hanya yang belum lewat';
        
        previewText.textContent = `Akan mengubah ${batchCount} jadwal menjadi "${statusText}" (${applyText})`;
        previewDiv.style.display = 'block';
    } else {
        previewDiv.style.display = 'none';
    }
}

function closeBatchStatusModal() {
    document.getElementById('batchStatusModal').style.display = 'none';
}

function confirmBatchUpdate() {
    const status = document.getElementById('batchStatus').value;
    const applyType = document.querySelector('input[name="applyType"]:checked').value;
    
    if (!status) {
        showToast('Pilih status terlebih dahulu', 'error');
        return;
    }
    
    // Filter schedules based on applyType
    let scheduleIdsToUpdate = selectedSchedules;
    let expiredSchedules = [];
    
    if (applyType === 'future') {
        const now = new Date();
        scheduleIdsToUpdate = [];
        expiredSchedules = [];
        
        selectedSchedules.forEach(id => {
            const card = document.querySelector(`.section-card[data-id="${id}"]`);
            if (card) {
                const isExpired = card.dataset.expired === 'true';
                if (!isExpired) {
                    scheduleIdsToUpdate.push(id);
                } else {
                    expiredSchedules.push(id);
                }
            }
        });
        
        if (scheduleIdsToUpdate.length === 0) {
            if (expiredSchedules.length > 0) {
                showToast('Semua jadwal yang dipilih sudah lewat', 'warning');
            } else {
                showToast('Tidak ada jadwal yang belum lewat untuk diupdate', 'warning');
            }
            return;
        }
    }
    
    // Show loading
    showLoading('Mengupdate Status', `Sedang mengubah ${scheduleIdsToUpdate.length} jadwal...`);
    
    // Send request to server
    fetch('/landowner/schedule/update-batch-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            ids: scheduleIdsToUpdate,
            status: status,
            apply_type: applyType
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        hideLoading();
        
        if (data.success) {
            let message = `✅ Berhasil mengubah ${data.updated} jadwal`;
            
            if (data.skipped && data.skipped > 0) {
                message += `, ${data.skipped} dilewati`;
                if (data.errors && data.errors.length > 0) {
                    // Show first error as toast
                    showToast(data.errors[0], 'warning');
                }
            }
            
            showToast(message, 'success');
            closeBatchStatusModal();
            
            // Clear selection
            clearSelection();
            
            // Reload schedules with animation
            const scheduleList = document.getElementById('scheduleList');
            scheduleList.classList.add('card-loading');
            
            setTimeout(() => {
                loadSchedules();
                setTimeout(() => {
                    scheduleList.classList.remove('card-loading');
                }, 500);
            }, 800);
        } else {
            showToast(data.message || '❌ Gagal mengupdate status', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error updating batch status:', error);
        showToast('❌ Terjadi kesalahan jaringan: ' + error.message, 'error');
    });
}

// ============================================
// FUNGSI EDIT SCHEDULE YANG DIPERBAIKI
// ============================================

function editSchedule(scheduleId) {
    // Gunakan endpoint yang benar untuk mendapatkan detail jadwal
    fetch(`/landowner/schedule/${scheduleId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const schedule = data.schedule;
                document.getElementById('editScheduleId').value = schedule.id;
                document.getElementById('editDate').value = schedule.date;
                document.getElementById('editStartTime').value = schedule.start_time;
                document.getElementById('editEndTime').value = schedule.end_time;
                document.getElementById('editRentalPrice').value = schedule.rental_price;
                document.getElementById('editAvailable').value = schedule.available ? '1' : '0';
                
                // Check if schedule is expired
                const now = new Date();
                const scheduleEnd = new Date(`${schedule.date}T${schedule.end_time}`);
                const isExpired = scheduleEnd < now;
                
                if (isExpired) {
                    showToast('Jadwal sudah lewat, hanya bisa dilihat', 'warning');
                }
                
                showEditScheduleModal();
            } else {
                showToast(data.message || 'Gagal memuat data jadwal', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal memuat data jadwal: ' + error.message, 'error');
        });
}

// Animation for Date Display
function addDateUpdateAnimation() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes dateUpdate {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.02); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .date-update-animation {
            animation: dateUpdate 0.3s ease;
        }
    `;
    document.head.appendChild(style);
}

// Initialize date update animation
addDateUpdateAnimation();
</script>