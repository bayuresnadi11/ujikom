<script>
    // Global variables
    let currentSlideIndex = 0;
    let slideInterval;
    const SLIDE_INTERVAL = 5000;
    let allSchedulesData = @json($upcomingSchedules);
    let nextScheduleIndex = {{ $gridSchedules->count() }};
    let currentlyDisplayedScheduleIds = new Set(@json($displaySchedules->pluck('id')->toArray()));

    // Clock Update Function
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
        
        return now;
    }

    // Scan status maps for display
    const scanStatusMapActive = {
        'belum_scan': { text: 'BELUM SCAN', class: 'status-belum-scan' },
        'masuk_venue': { text: 'MASUK VENUE', class: 'status-masuk-venue' },
        'masuk_lapang': { text: 'MASUK LAPANG', class: 'status-masuk-lapang' }
    };
const carouselEl = document.querySelector('#sectionCarousel');

    if (carouselEl) {
        const carousel = new bootstrap.Carousel(carouselEl, {
            interval: false, // ⛔ matikan auto bawaan
            ride: false,
            wrap: false      // ⛔ jangan looping otomatis
        });

        const items = carouselEl.querySelectorAll('.carousel-item');
        let index = 0;
        let direction = 'forward'; // forward = ke kanan

        setInterval(() => {
            if (direction === 'forward') {
                if (index < items.length - 1) {
                    index++;
                    carousel.next();
                } else {
                    direction = 'backward'; // 🔁 balik arah
                    index--;
                    carousel.prev();
                }
            } else {
                if (index > 0) {
                    index--;
                    carousel.prev();
                } else {
                    direction = 'forward'; // 🔁 balik lagi ke kanan
                    index++;
                    carousel.next();
                }
            }
        }, 5000); // 5 detik
    }
    // Helper function to get active status class
    function getActiveStatusClass(schedule) {
        if (schedule.is_paid && schedule.scan_status && scanStatusMapActive[schedule.scan_status]) {
            return scanStatusMapActive[schedule.scan_status].class;
        } else if (schedule.isBooked || schedule.is_booked) {
            return 'status-booked';
        }
        return 'status-available';
    }

    // Helper function to get active status text
    function getActiveStatusText(schedule) {
        if (schedule.is_paid && schedule.scan_status && scanStatusMapActive[schedule.scan_status]) {
            return scanStatusMapActive[schedule.scan_status].text;
        } else if (schedule.isBooked || schedule.is_booked) {
            return 'TERISI';
        }
        return 'KOSONG';
    }

    // Function to show a specific slide with sliding animation
    function showSlide(index) {
        const slides = document.querySelectorAll('.schedule-slide');
        const indicators = document.querySelectorAll('.slide-indicator');
        const slideWrapper = document.getElementById('slideWrapper');
        
        if (slides.length === 0) return;
        
        // Wrap around if index is out of bounds
        if (index >= slides.length) index = 0;
        if (index < 0) index = slides.length - 1;
        
        // Calculate the translate value
        const translateValue = -index * 100;
        
        // Apply transform for sliding effect
        if (slideWrapper) {
            slideWrapper.style.transform = `translateX(${translateValue}%)`;
        }
        
        // Update active indicator
        indicators.forEach((indicator, i) => {
            if (i === index) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
        
        currentSlideIndex = index;
    }

    // Function to go to next slide
    function nextSlide() {
        const slides = document.querySelectorAll('.schedule-slide');
        if (slides.length > 1) {
            let nextIndex = currentSlideIndex + 1;
            if (nextIndex >= slides.length) nextIndex = 0;
            showSlide(nextIndex);
        }
    }

    // Function to start slide show
    function startSlideShow() {
        const slides = document.querySelectorAll('.schedule-slide');
        if (slides.length > 1) {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, SLIDE_INTERVAL);
        }
    }

    // Function to check if a schedule is current
    function isScheduleCurrent(startTime, endTime, currentTime) {
        const [startHour, startMinute] = startTime.split(':').map(Number);
        const [endHour, endMinute] = endTime.split(':').map(Number);
        const [currentHour, currentMinute] = currentTime.split(':').map(Number);
        
        const startTotal = startHour * 60 + startMinute;
        const endTotal = endHour * 60 + endMinute;
        const currentTotal = currentHour * 60 + currentMinute;
        
        return currentTotal >= startTotal && currentTotal <= endTotal;
    }

    // Function to check if a schedule has passed
    function isSchedulePassed(endTime, currentTime) {
        const [endHour, endMinute] = endTime.split(':').map(Number);
        const [currentHour, currentMinute] = currentTime.split(':').map(Number);
        
        const endTotal = endHour * 60 + endMinute;
        const currentTotal = currentHour * 60 + currentMinute;
        
        return currentTotal > endTotal;
    }

    // Function to update schedule status in real-time
    function updateScheduleStatus() {
        const now = new Date();
        const currentHour = now.getHours().toString().padStart(2, '0');
        const currentMinute = now.getMinutes().toString().padStart(2, '0');
        const currentTime = `${currentHour}:${currentMinute}`;
        
        const scheduleCards = document.querySelectorAll('.schedule-card');
        let newCurrentSchedules = [];
        let schedulesToRemoveFromGrid = [];
        
        // Check each schedule card in grid
        scheduleCards.forEach(card => {
            const scheduleId = card.dataset.scheduleId;
            const startTime = card.dataset.startTime;
            const endTime = card.dataset.endTime;
            const isCurrent = isScheduleCurrent(startTime, endTime, currentTime);
            
            // If schedule becomes current and NOT already displayed in big panel
            if (isCurrent && !currentlyDisplayedScheduleIds.has(scheduleId)) {
                // Add to current schedules
                newCurrentSchedules.push({
                    element: card,
                    scheduleId: scheduleId,
                    startTime: startTime,
                    endTime: endTime,
                    section: card.dataset.section,
                    isBooked: card.dataset.isBooked === 'true',
                    customerName: card.querySelector('.schedule-customer-name')?.textContent || 'KOSONG'
                });
                
                // Mark to remove from grid
                schedulesToRemoveFromGrid.push(card);
                currentlyDisplayedScheduleIds.add(scheduleId);
            }
            
            // Remove passed schedules
            if (isSchedulePassed(endTime, currentTime)) {
                schedulesToRemoveFromGrid.push(card);
            }
        });
        
        // Remove schedules from grid
        schedulesToRemoveFromGrid.forEach(card => {
            card.style.animation = 'fadeOut 0.5s forwards';
            setTimeout(() => {
                if (card.parentNode) {
                    card.parentNode.removeChild(card);
                }
            }, 500);
        });
        
        // Update display panel if there are new current schedules
        if (newCurrentSchedules.length > 0) {
            updateDisplayPanelWithNewSchedules(newCurrentSchedules);
        }
        
        // Check if we need to remove schedules from display panel (if they're no longer current)
        updateDisplayPanelForEndedSchedules(currentTime);
        
        // Add new upcoming schedules to grid (excluding those already displayed)
        const cardsToAdd = schedulesToRemoveFromGrid.length;
        for (let i = 0; i < cardsToAdd; i++) {
            if (nextScheduleIndex < allSchedulesData.length) {
                const nextSchedule = allSchedulesData[nextScheduleIndex];
                
                // Skip if this schedule is already displayed in big panel
                if (currentlyDisplayedScheduleIds.has(nextSchedule.id)) {
                    nextScheduleIndex++;
                    i--; // Try next schedule
                    continue;
                }
                
                // Check if it's current now
                const isNowCurrent = isScheduleCurrent(nextSchedule.start_time, nextSchedule.end_time, currentTime);
                if (isNowCurrent) {
                    // This schedule should go to display panel, not grid
                    currentlyDisplayedScheduleIds.add(nextSchedule.id);
                    nextScheduleIndex++;
                    i--; // Try next schedule
                    continue;
                }
                
                // Create new card for grid
                const grid = document.getElementById('schedulesGrid');
                const newCard = document.createElement('div');
                const colorIndex = nextScheduleIndex % 6;
                
                const customerName = nextSchedule.customer_name || 'KOSONG';
                
                // Determine status for grid display
                let statusClass, badgeText;
                const scanStatusMap = {
                    'belum_scan': { text: 'BELUM SCAN', class: 'badge-belum-scan' },
                    'masuk_venue': { text: 'MASUK VENUE', class: 'badge-masuk-venue' },
                    'masuk_lapang': { text: 'MASUK LAPANG', class: 'badge-masuk-lapang' }
                };
                
                if (nextSchedule.is_paid && nextSchedule.scan_status && scanStatusMap[nextSchedule.scan_status]) {
                    statusClass = scanStatusMap[nextSchedule.scan_status].class;
                    badgeText = scanStatusMap[nextSchedule.scan_status].text;
                } else if (nextSchedule.is_booked) {
                    statusClass = 'badge-booked';
                    badgeText = 'TERISI';
                } else {
                    statusClass = 'badge-available';
                    badgeText = 'KOSONG';
                }
                
                newCard.className = `schedule-card color-${colorIndex}`;
                newCard.dataset.scheduleId = nextSchedule.id;
                newCard.dataset.startTime = nextSchedule.start_time;
                newCard.dataset.endTime = nextSchedule.end_time;
                newCard.dataset.section = nextSchedule.section_name;
                newCard.dataset.isBooked = nextSchedule.is_booked ? 'true' : 'false';
                newCard.dataset.isPaid = nextSchedule.is_paid ? 'true' : 'false';
                newCard.dataset.scanStatus = nextSchedule.scan_status || '';
                newCard.dataset.isCurrent = 'false';
                
                newCard.innerHTML = `
                    <div class="schedule-card-body">
                        <div class="schedule-header">
                            <div class="schedule-time">
                                ${nextSchedule.start_time} - ${nextSchedule.end_time}
                            </div>
                            <div class="schedule-badge ${statusClass}">
                                ${badgeText}
                            </div>
                        </div>
                        
                        <div class="schedule-details">
                            <div class="schedule-section-name">${nextSchedule.section_name}</div>
                            <div class="schedule-customer-name">${customerName}</div>
                        </div>
                    </div>
                `;
                
                if (grid) {
                    grid.appendChild(newCard);
                }
                
                nextScheduleIndex++;
            }
        }
        
        // Update the upcoming count
        const remainingCards = document.querySelectorAll('.schedule-card:not([style*="fadeOut"])');
        document.getElementById('upcomingCount').textContent = remainingCards.length;
        
        // Update empty state if needed
        updateEmptyState();
    }

    // Function to update display panel with new current schedules
    function updateDisplayPanelWithNewSchedules(newCurrentSchedules) {
        const slideWrapper = document.getElementById('slideWrapper');
        const cardHeader = document.querySelector('.active-schedule-card .card-header');
        const activeScheduleBody = document.getElementById('activeScheduleBody');
        
        if (cardHeader) {
            cardHeader.textContent = 'JADWAL SEDANG BERLANGSUNG';
        }
        
        // Stop current slideshow
        clearInterval(slideInterval);
        
        if (slideWrapper) {
            // Get existing slides
            const existingSlides = Array.from(slideWrapper.querySelectorAll('.schedule-slide'));
            
            // Add new slides
            newCurrentSchedules.forEach((schedule, index) => {
                const slide = document.createElement('div');
                slide.className = 'schedule-slide';
                slide.dataset.scheduleId = schedule.scheduleId;
                
                // Determine status logic
                const isPaid = schedule.isPaid;
                const scanStatus = schedule.scanStatus;
                const isMasukLapang = isPaid && scanStatus === 'masuk_lapang';
                const isBelumScan = isPaid && scanStatus === 'belum_scan';
                const isMasukVenue = isPaid && scanStatus === 'masuk_venue';
                
                let timeClass = '';
                if (isBelumScan) {
                    timeClass = 'time-belum-scan';
                } else if (isMasukVenue) {
                    timeClass = 'time-masuk-venue';
                }
                
                let contentHTML = '';
                
                if (isMasukLapang) {
                    contentHTML = `
                        <div class="countdown-container">
                            <div class="countdown-label">SISA WAKTU MAIN</div>
                            <div class="countdown-timer" 
                                 id="countdownTimer-${index}"
                                 data-end-time="${schedule.endTime}">
                                --:--:--
                            </div>
                            <div class="countdown-schedule">
                                ${schedule.startTime} - ${schedule.endTime}
                            </div>
                            <div class="countdown-section">
                                ${schedule.section}
                            </div>
                        </div>
                    `;
                } else {
                    contentHTML = `
                        <div class="current-schedule-time ${timeClass}">${schedule.startTime} - ${schedule.endTime}</div>
                        <div class="current-schedule-label">WAKTU MAIN</div>
                        
                        <div class="current-schedule-details">
                            <div class="schedule-section">${schedule.section}</div>
                            <div class="schedule-customer">${schedule.customerName}</div>
                        </div>
                        
                        <div class="schedule-status ${getActiveStatusClass({is_paid: isPaid, scan_status: scanStatus, is_booked: schedule.isBooked})}">
                            ${getActiveStatusText({is_paid: isPaid, scan_status: scanStatus, is_booked: schedule.isBooked})} 
                        </div>
                    `;
                }

                slide.innerHTML = contentHTML;
                
                slideWrapper.appendChild(slide);
            });
            
            // Update indicators
            updateSlideIndicators();
            
            // Reset slide index and start slideshow
            currentSlideIndex = 0;
            slideWrapper.style.transform = 'translateX(0%)';
            
            if (existingSlides.length + newCurrentSchedules.length > 1) {
                startSlideShow();
            }
        } else {
            // Create new display panel with current schedules
            updateDisplayPanel(newCurrentSchedules, true);
        }
    }

    // Function to check and remove ended schedules from display panel
    function updateDisplayPanelForEndedSchedules(currentTime) {
        const slideWrapper = document.getElementById('slideWrapper');
        if (!slideWrapper) return;
        
        const slides = slideWrapper.querySelectorAll('.schedule-slide');
        const slidesToRemove = [];
        
        slides.forEach(slide => {
            const scheduleId = slide.dataset.scheduleId;
            // Find schedule data
            const scheduleData = allSchedulesData.find(s => s.id == scheduleId);
            if (scheduleData) {
                if (isSchedulePassed(scheduleData.end_time, currentTime)) {
                    slidesToRemove.push(slide);
                    currentlyDisplayedScheduleIds.delete(parseInt(scheduleId));
                }
            }
        });
        
        // Remove ended schedules
        slidesToRemove.forEach(slide => {
            slide.remove();
        });
        
        // If no more slides in display panel, show next upcoming schedules
        if (slides.length - slidesToRemove.length === 0) {
            showNextUpcomingSchedules();
        } else if (slides.length - slidesToRemove.length > 1) {
            // Update indicators if multiple slides remain
            updateSlideIndicators();
        }
    }

    // Function to update slide indicators
    function updateSlideIndicators() {
        const slideWrapper = document.getElementById('slideWrapper');
        const indicatorsContainer = document.getElementById('slideIndicators');
        
        if (!slideWrapper || !indicatorsContainer) return;
        
        const slides = slideWrapper.querySelectorAll('.schedule-slide');
        
        indicatorsContainer.innerHTML = '';
        
        if (slides.length > 1) {
            for (let i = 0; i < slides.length; i++) {
                const indicator = document.createElement('div');
                indicator.className = `slide-indicator ${i === 0 ? 'active' : ''}`;
                indicator.dataset.index = i;
                indicator.addEventListener('click', () => {
                    showSlide(i);
                    resetSlideTimer();
                });
                indicatorsContainer.appendChild(indicator);
            }
            indicatorsContainer.style.display = 'flex';
        } else {
            indicatorsContainer.style.display = 'none';
        }
    }

    // Function to show next upcoming schedules in display panel
    function showNextUpcomingSchedules() {
        const gridCards = Array.from(document.querySelectorAll('.schedule-card:not([style*="fadeOut"])'));
        const upcomingSchedules = [];
        
        // Find next 2 schedules that are not current
        for (let card of gridCards) {
            if (upcomingSchedules.length >= 2) break;
            
            const scheduleId = card.dataset.scheduleId;
            if (!currentlyDisplayedScheduleIds.has(scheduleId)) {
                upcomingSchedules.push({
                    element: card,
                    scheduleId: scheduleId,
                    startTime: card.dataset.startTime,
                    endTime: card.dataset.endTime,
                    section: card.dataset.section,
                    isBooked: card.dataset.isBooked === 'true',
                    isPaid: card.dataset.isPaid === 'true',
                    scanStatus: card.dataset.scanStatus,
                    customerName: card.querySelector('.schedule-customer-name')?.textContent || 'KOSONG'
                });
            }
        }
        
        if (upcomingSchedules.length > 0) {
            updateDisplayPanel(upcomingSchedules, false);
            // Remove these from grid
            upcomingSchedules.forEach(schedule => {
                schedule.element.remove();
                currentlyDisplayedScheduleIds.add(schedule.scheduleId);
            });
        } else {
            // No schedules at all
            showNoSchedulesInDisplayPanel();
        }
    }

    // Function to update display panel
    function updateDisplayPanel(schedulesData, isCurrent = true) {
        const slideWrapper = document.getElementById('slideWrapper');
        const indicators = document.getElementById('slideIndicators');
        const cardHeader = document.querySelector('.active-schedule-card .card-header');
        const activeScheduleBody = document.getElementById('activeScheduleBody');
        
        // Stop current slideshow
        clearInterval(slideInterval);
        
        // Update header
        if (cardHeader) {
            if (isCurrent) {
                cardHeader.textContent = 'JADWAL SEDANG BERLANGSUNG';
            } else {
                cardHeader.textContent = 'JADWAL BERIKUTNYA';
            }
        }
        
        // Clear existing slides
        if (slideWrapper) {
            slideWrapper.innerHTML = '';
        } else {
            // Create slide wrapper if it doesn't exist
            const newSlideWrapper = document.createElement('div');
            newSlideWrapper.className = 'slide-wrapper';
            newSlideWrapper.id = 'slideWrapper';
            activeScheduleBody.appendChild(newSlideWrapper);
        }
        
        const currentSlideWrapper = document.getElementById('slideWrapper');
        
        // Create new slides
        schedulesData.forEach((schedule, index) => {
            const slide = document.createElement('div');
            slide.className = 'schedule-slide';
            slide.dataset.index = index;
            slide.dataset.scheduleId = schedule.scheduleId;
            
            const statusText = isCurrent ? ' - SEDANG BERMAIN' : '';
            
            slide.innerHTML = `
                <div class="current-schedule-time">${schedule.startTime} - ${schedule.endTime}</div>
                <div class="current-schedule-label">WAKTU MAIN</div>
                
                <div class="current-schedule-details">
                    <div class="schedule-section">${schedule.section}</div>
                    <div class="schedule-customer">${schedule.customerName}</div>
                </div>
                
                <div class="schedule-status ${schedule.isBooked ? 'status-booked' : 'status-available'}">
                    ${schedule.isBooked ? 'TERISI' : 'KOSONG'}${statusText}
                </div>
            `;
            
            if (currentSlideWrapper) {
                currentSlideWrapper.appendChild(slide);
            }
        });
        
        // Update indicators
        updateSlideIndicators();
        
        // Reset slide index and position
        currentSlideIndex = 0;
        if (currentSlideWrapper) {
            currentSlideWrapper.style.transform = 'translateX(0%)';
        }
        
        // Start slideshow if there are multiple schedules
        if (schedulesData.length > 1) {
            startSlideShow();
        }
    }

    // Function to show no schedules message in display panel
    function showNoSchedulesInDisplayPanel() {
        const cardHeader = document.querySelector('.active-schedule-card .card-header');
        const activeScheduleBody = document.getElementById('activeScheduleBody');
        
        if (cardHeader) {
            cardHeader.textContent = 'TIDAK ADA JADWAL';
        }
        
        if (activeScheduleBody) {
            // Check if empty state already exists
            if (!activeScheduleBody.querySelector('.empty-state')) {
                activeScheduleBody.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="empty-title">TIDAK ADA JADWAL</div>
                        <div class="empty-subtitle">Tidak ada jadwal hari ini</div>
                    </div>
                `;
            }
        }
        
        const indicators = document.getElementById('slideIndicators');
        if (indicators) indicators.style.display = 'none';
        clearInterval(slideInterval);
    }

    // Function to update empty state
    function updateEmptyState() {
        const gridContainer = document.querySelector('.schedules-container');
        const grid = document.getElementById('schedulesGrid');
        const scheduleCards = grid ? grid.querySelectorAll('.schedule-card:not([style*="fadeOut"])') : [];
        
        // Check if grid is empty
        if (scheduleCards.length === 0 && gridContainer) {
            const existingEmptyState = gridContainer.querySelector('.empty-state');
            if (!existingEmptyState) {
                const emptyState = document.createElement('div');
                emptyState.className = 'empty-state';
                emptyState.innerHTML = `
                    <i class="fas fa-calendar-times fa-4x mb-3" style="color: var(--text-light); opacity: 0.5;"></i>
                    <div class="empty-title">TIDAK ADA JADWAL</div>
                    <div class="empty-subtitle">Tidak ada jadwal di venue ini untuk hari ini</div>
                `;
                
                if (grid) {
                    grid.innerHTML = '';
                    grid.appendChild(emptyState);
                } else {
                    gridContainer.appendChild(emptyState);
                }
            }
        }
    }

    // Function to reset slide timer
    function resetSlideTimer() {
        clearInterval(slideInterval);
        const slides = document.querySelectorAll('.schedule-slide');
        if (slides.length > 1) {
            startSlideShow();
        }
    }

    // Countdown timer function for masuk_lapang - updates all countdown timers
    function updateAllCountdownTimers() {
        const countdownTimers = document.querySelectorAll('.countdown-timer');
        if (countdownTimers.length === 0) return;
        
        const now = new Date();
        
        countdownTimers.forEach(countdownTimer => {
            const endTimeStr = countdownTimer.dataset.endTime;
            if (!endTimeStr) return;
            
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
            }
        });
    }

    // Main refresh function
    function refreshDisplay() {
        updateClock();
        updateScheduleStatus();
    }

    // Initialize everything
    document.addEventListener('DOMContentLoaded', function() {
        // Start clock
        updateClock();
        setInterval(updateClock, 1000);
        
        // Start countdown timer updates
        updateAllCountdownTimers();
        setInterval(updateAllCountdownTimers, 1000);
        
        // Initialize schedule status after a short delay
        setTimeout(updateScheduleStatus, 100);
        
        // Start slide show if there are slides
        const slides = document.querySelectorAll('.schedule-slide');
        if (slides.length > 1) {
            startSlideShow();
        }
        
        // Add click events to slide indicators
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('slide-indicator')) {
                const index = parseInt(e.target.dataset.index);
                showSlide(index);
                resetSlideTimer();
            }
        });
        
        // Auto-refresh entire page every 10 seconds (Requested by User)
        setTimeout(() => {
            window.location.reload();
        }, 10000);
    });

    // Hover effects for schedule cards
    document.addEventListener('mouseover', function(e) {
        const card = e.target.closest('.schedule-card');
        if (card) {
            card.style.transform = 'translateY(-6px)';
            card.style.boxShadow = 'var(--shadow-xl)';
        }
    });

    document.addEventListener('mouseout', function(e) {
        const card = e.target.closest('.schedule-card');
        if (card) {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'var(--shadow-lg)';
        }
    });
</script>