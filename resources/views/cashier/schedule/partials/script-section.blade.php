<script>
    // Simpan data awal
    let allSchedules = @json($upcomingSchedules);
    let currentSlotId = @json($currentSlot ? ($currentSlot['id'] ?? $currentIndex) : null);
    let lastRefreshTime = new Date().getTime();
    
    // Clock Update Function (jam berjalan real-time setiap detik)
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        const dateString = now.toLocaleDateString('id-ID', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });

        document.getElementById('clockTime').textContent = timeString;
        document.getElementById('clockDate').textContent = dateString;
        
        return {
            time: now.getHours().toString().padStart(2, '0') + ':' + 
                  now.getMinutes().toString().padStart(2, '0'),
            fullTime: timeString,
            timestamp: now.getTime()
        };
    }

    // Function untuk update tampilan berdasarkan waktu saat ini
    function updateDisplay() {
        const clockInfo = updateClock();
        const currentTime = clockInfo.time;
        
        // Filter jadwal yang belum lewat
        const validSchedules = allSchedules.filter(schedule => {
            return schedule.end_time > currentTime;
        });
        
        // Cari slot yang sedang berlangsung
        let currentSlot = null;
        let currentSlotIndex = -1;
        
        for (let i = 0; i < validSchedules.length; i++) {
            const schedule = validSchedules[i];
            if (currentTime >= schedule.start_time && currentTime <= schedule.end_time) {
                currentSlot = schedule;
                currentSlotIndex = i;
                break;
            }
        }
        
        // Jika tidak ada yang sedang berlangsung, ambil slot berikutnya
        if (!currentSlot && validSchedules.length > 0) {
            currentSlot = validSchedules[0];
            currentSlotIndex = 0;
        }
        
        // Update current slot box
        updateCurrentSlotBox(currentSlot);
        
        // Update grid schedules (tanpa duplikat dengan current slot)
        updateGridSchedules(validSchedules, currentSlotIndex, currentSlot);
        
        // Update last refresh time
        lastRefreshTime = clockInfo.timestamp;
    }

    // Function untuk update current slot box
    function updateCurrentSlotBox(currentSlot) {
        const currentSlotBody = document.getElementById('currentSlotBody');
        
        if (currentSlot) {
            const customerName = currentSlot.customer_name || currentSlot.community_name || 'LAPANGAN KOSONG';
            const isBooked = currentSlot.is_booked;
            const isPaid = currentSlot.is_paid;
            const scanStatus = currentSlot.scan_status;
            
            // Check if masuk_lapang - show countdown
            const isMasukLapang = isPaid && scanStatus === 'masuk_lapang';
            const isBelumScan = isPaid && scanStatus === 'belum_scan';
            const isMasukVenue = isPaid && scanStatus === 'masuk_venue';
            
            // Determine time class
            let timeClass = '';
            if (isBelumScan) {
                timeClass = 'time-belum-scan';
            } else if (isMasukVenue) {
                timeClass = 'time-masuk-venue';
            }
            
            // Determine status text and class
            let slotStatusText, slotStatusClass;
            const scanStatusMap = {
                'belum_scan': { text: 'BELUM SCAN', class: 'slot-status-belum-scan' },
                'masuk_venue': { text: 'MASUK VENUE', class: 'slot-status-masuk-venue' }
            };
            
            if (isPaid && scanStatus && scanStatusMap[scanStatus]) {
                slotStatusText = scanStatusMap[scanStatus].text;
                slotStatusClass = scanStatusMap[scanStatus].class;
            } else if (isBooked) {
                slotStatusText = 'TERISI';
                slotStatusClass = 'slot-status-booked';
            } else {
                slotStatusText = 'KOSONG';
                slotStatusClass = 'slot-status-available';
            }
            
            if (isMasukLapang) {
                // Countdown timer display for masuk_lapang
                currentSlotBody.innerHTML = `
                    <div class="countdown-container">
                        <div class="countdown-label">SISA WAKTU MAIN</div>
                        <div class="countdown-timer" id="countdownTimer" data-end-time="${currentSlot.end_time}">
                            --:--:--
                        </div>
                        <div class="countdown-schedule">
                            ${currentSlot.start_time} - ${currentSlot.end_time}
                        </div>
                    </div>
                `;
                // Start countdown immediately
                updateCountdownTimer();
            } else {
                // Normal display with status badge
                currentSlotBody.innerHTML = `
                    <div class="current-slot-time ${timeClass}">${currentSlot.start_time} - ${currentSlot.end_time}</div>
                    <div class="current-slot-label">WAKTU MAIN</div>
                    <div class="current-slot-name">${customerName}</div>
                    <div class="slot-status ${slotStatusClass}">
                        ${slotStatusText}
                    </div>
                `;
            }
        } else {
            currentSlotBody.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="empty-title">TIDAK ADA JADWAL</div>
                    <div class="empty-subtitle">Tidak ada jadwal untuk hari ini</div>
                </div>
            `;
        }
    }

    // Function untuk update grid schedules
    function updateGridSchedules(schedules, currentIndex, currentSlot) {
        const scheduleGrid = document.getElementById('scheduleGrid');
        
        // Filter out current slot dari grid (agar tidak duplikat)
        const gridSchedules = schedules.filter((_, index) => index !== currentIndex);
        
        // Batasi maksimal 14 item di grid (karena 1 sudah di box besar)
        const displaySchedules = gridSchedules.slice(0, 14);
        
        if (displaySchedules.length > 0) {
            let gridHTML = '';
            
            displaySchedules.forEach((schedule, index) => {
                const customerName = schedule.customer_name || schedule.community_name || 'KOSONG';
                const isBooked = schedule.is_booked;
                const isPaid = schedule.is_paid;
                const scanStatus = schedule.scan_status;
                
                // Determine status text and class
                let status, statusClass;
                const scanStatusMap = {
                    'belum_scan': { text: 'BELUM SCAN', class: 'status-belum-scan' },
                    'masuk_venue': { text: 'MASUK VENUE', class: 'status-masuk-venue' },
                    'masuk_lapang': { text: 'MASUK LAPANG', class: 'status-masuk-lapang' }
                };
                
                if (isPaid && scanStatus && scanStatusMap[scanStatus]) {
                    status = scanStatusMap[scanStatus].text;
                    statusClass = scanStatusMap[scanStatus].class;
                } else if (isBooked) {
                    status = 'TERISI';
                    statusClass = 'status-booked';
                } else {
                    status = 'KOSONG';
                    statusClass = 'status-available';
                }
                
                gridHTML += `
                    <div class="schedule-card color-${index % 6}" 
                         data-start="${schedule.start_time}"
                         data-end="${schedule.end_time}"
                         data-id="${schedule.id || index}">
                        <div class="schedule-card-body">
                            <div class="schedule-time">${schedule.start_time} - ${schedule.end_time}</div>
                            <div class="schedule-customer">${customerName}</div>
                            <div class="schedule-status ${statusClass}">${status}</div>
                        </div>
                    </div>
                `;
            });
            
            scheduleGrid.innerHTML = gridHTML;
            
            // Add hover effects untuk cards baru
            addHoverEffects();
        } else {
            scheduleGrid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-4x mb-3" style="color: var(--text-light); opacity: 0.5;"></i>
                    <div class="empty-title">TIDAK ADA JADWAL MENDATANG</div>
                    <div class="empty-subtitle">Tidak ada jadwal lain hari ini</div>
                </div>
            `;
        }
    }

    // Function untuk fetch data baru dari server
    async function fetchNewData() {
        try {
            // Gunakan AJAX untuk fetch data baru tanpa reload halaman
            const response = await fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const html = await response.text();
                
                // Extract data dari HTML response (ini adalah pendekatan sederhana)
                // Dalam implementasi real, sebaiknya backend mengembalikan JSON
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Update schedules dari response
                // Note: Ini adalah workaround sederhana
                // Di production, backend harus mengembalikan data JSON
                
                // Untuk saat ini, kita hanya update timestamp
                console.log('Data refreshed at:', new Date().toLocaleTimeString());
            }
        } catch (error) {
            console.log('Error fetching data:', error);
        }
    }

    // Function untuk menambahkan hover effects
    function addHoverEffects() {
        const scheduleCards = document.querySelectorAll('.schedule-card');
        
        scheduleCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-6px)';
                this.style.boxShadow = '0 12px 40px rgba(10, 92, 54, 0.18)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'var(--shadow-lg)';
            });
        });
    }

    // Countdown timer function for masuk_lapang
    function updateCountdownTimer() {
        const countdownTimer = document.getElementById('countdownTimer');
        if (!countdownTimer) return;
        
        const endTimeStr = countdownTimer.dataset.endTime;
        if (!endTimeStr) return;
        
        const now = new Date();
        const [endHours, endMinutes] = endTimeStr.split(':').map(Number);
        
        // Create end time Date object for today
        const endTime = new Date();
        endTime.setHours(endHours, endMinutes, 0, 0);
        
        // Calculate difference in seconds
        const diffMs = endTime - now;
        const diffSeconds = Math.floor(diffMs / 1000);
        
        if (diffSeconds <= 0) {
            countdownTimer.textContent = '00:00:00';
            countdownTimer.style.color = '#c0392b';
            return;
        }
        
        // Convert to hours, minutes and seconds
        const hours = Math.floor(diffSeconds / 3600);
        const minutes = Math.floor((diffSeconds % 3600) / 60);
        const seconds = diffSeconds % 60;
        
        // Format as HH:MM:SS
        const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        countdownTimer.textContent = formattedTime;
        
        // Change color when less than 5 minutes
        if (hours === 0 && minutes < 5) {
            countdownTimer.style.color = '#c0392b';
            countdownTimer.style.animation = 'pulse 1s infinite';
        }
    }

    // Main function untuk mengatur semua interval
    function initializeDisplay() {
        // Update jam setiap detik (real-time)
        setInterval(updateClock, 1000);
        
        // Update countdown timer every second
        setInterval(updateCountdownTimer, 1000);
        
        // Auto-refresh entire page every 10 seconds (Requested by User)
        setTimeout(() => {
            window.location.reload();
        }, 10000);
        
        // Initial update
        updateClock();
        updateCountdownTimer();
        updateDisplay();
        addHoverEffects();
    }

    // Start everything ketika DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        initializeDisplay();
    });
</script>