<script>
    let allSchedules = @json($upcomingSchedules);
    let currentSlotId = @json($currentSlot ? ($currentSlot['id'] ?? $currentIndex) : null);
    let lastRefreshTime = new Date().getTime();
    
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

    function updateDisplay() {
        const clockInfo = updateClock();
        const currentTime = clockInfo.time;
        
        const validSchedules = allSchedules.filter(schedule => {
            return schedule.end_time > currentTime;
        });
        
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
        
        if (!currentSlot && validSchedules.length > 0) {
            currentSlot = validSchedules[0];
            currentSlotIndex = 0;
        }
        
        updateCurrentSlotBox(currentSlot);
        
        updateGridSchedules(validSchedules, currentSlotIndex, currentSlot);
        
        lastRefreshTime = clockInfo.timestamp;
    }

    function updateCurrentSlotBox(currentSlot) {
        const currentSlotBody = document.getElementById('currentSlotBody');
        
        if (currentSlot) {
            const customerName = currentSlot.customer_name || currentSlot.community_name || 'LAPANGAN KOSONG';
            const isBooked = currentSlot.is_booked;
            const isPaid = currentSlot.is_paid;
            const scanStatus = currentSlot.scan_status;
            
            const isMasukLapang = isPaid && scanStatus === 'masuk_lapang';
            const isBelumScan = isPaid && scanStatus === 'belum_scan';
            const isMasukVenue = isPaid && scanStatus === 'masuk_venue';
            
            let timeClass = '';
            if (isBelumScan) {
                timeClass = 'time-belum-scan';
            } else if (isMasukVenue) {
                timeClass = 'time-masuk-venue';
            }
            
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
                updateCountdownTimer();
            } else {
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

    function updateGridSchedules(schedules, currentIndex, currentSlot) {
        const scheduleGrid = document.getElementById('scheduleGrid');
        
        const gridSchedules = schedules.filter((_, index) => index !== currentIndex);
        
        const displaySchedules = gridSchedules.slice(0, 14);
        
        if (displaySchedules.length > 0) {
            let gridHTML = '';
            
            displaySchedules.forEach((schedule, index) => {
                const customerName = schedule.customer_name || schedule.community_name || 'KOSONG';
                const isBooked = schedule.is_booked;
                const isPaid = schedule.is_paid;
                const scanStatus = schedule.scan_status;
                
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

    async function fetchNewData() {
        try {
            const response = await fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const html = await response.text();
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                console.log('Data refreshed at:', new Date().toLocaleTimeString());
            }
        } catch (error) {
            console.log('Error fetching data:', error);
        }
    }

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

    function updateCountdownTimer() {
        const countdownTimer = document.getElementById('countdownTimer');
        if (!countdownTimer) return;
        
        const endTimeStr = countdownTimer.dataset.endTime;
        if (!endTimeStr) return;
        
        const now = new Date();
        const [endHours, endMinutes] = endTimeStr.split(':').map(Number);
        
        const endTime = new Date();
        endTime.setHours(endHours, endMinutes, 0, 0);
        
        const diffMs = endTime - now;
        const diffSeconds = Math.floor(diffMs / 1000);
        
        if (diffSeconds <= 0) {
            countdownTimer.textContent = '00:00:00';
            countdownTimer.style.color = '#c0392b';
            return;
        }
        
        const hours = Math.floor(diffSeconds / 3600);
        const minutes = Math.floor((diffSeconds % 3600) / 60);
        const seconds = diffSeconds % 60;
        
        const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        countdownTimer.textContent = formattedTime;
        
        if (hours === 0 && minutes < 5) {
            countdownTimer.style.color = '#c0392b';
            countdownTimer.style.animation = 'pulse 1s infinite';
        }
    }

    function initializeDisplay() {
        setInterval(updateClock, 1000);
        
        setInterval(updateCountdownTimer, 1000);
        
        setTimeout(() => {
            window.location.reload();
        }, 10000);
        
        updateClock();
        updateCountdownTimer();
        updateDisplay();
        addHoverEffects();
    }

    document.addEventListener('DOMContentLoaded', function() {
        initializeDisplay();
    });
</script>