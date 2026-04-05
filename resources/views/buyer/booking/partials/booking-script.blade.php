// CSRF Token Setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Global Variables
window.currentBookingId = null;
let selectedSectionId = null;
let selectedScheduleId = null;
let selectedSchedulePrice = 0;

// ========================================
// MODAL FUNCTIONS
// ========================================
function openModalTambah(venueId = null) {
    document.getElementById('modalForm').style.display = 'flex';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Buat Booking Baru';
    document.getElementById('bookingForm').reset();
    document.getElementById('booking_id').value = '';
    document.getElementById('btnSubmit').disabled = true;
    
    // Reset sections dan schedules
    document.getElementById('sectionGroup').style.display = 'none';
    document.getElementById('scheduleGroup').style.display = 'none';
    document.getElementById('scheduleInfo').style.display = 'none';
    document.getElementById('playTogetherForm').style.display = 'none';
    document.getElementById('sparringForm').style.display = 'none';
    document.getElementById('costCalculator').style.display = 'none';
    selectedSectionId = null;
    selectedScheduleId = null;
    selectedSchedulePrice = 0;
    
    // Jika venueId diberikan (dari halaman show venue), auto-select dan load sections
    if (venueId) {
        document.getElementById('venue_id').value = venueId;
        loadSections(venueId);
    }
}

function closeModal() {
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.style.display = 'none';
    });
}

// ========================================
// HANDLE TYPE CHANGE
// ========================================
function handleTypeChange() {
    const type = document.getElementById('type').value;
    const playTogetherForm = document.getElementById('playTogetherForm');
    const sparringForm = document.getElementById('sparringForm');
    
    // Hide both forms
    playTogetherForm.style.display = 'none';
    sparringForm.style.display = 'none';
    
    // Show appropriate form
    if (type === 'play_together') {
        playTogetherForm.style.display = 'block';
        // Set default privacy to public
        document.getElementById('pt_privacy').value = 'public';
        handlePrivacyChange();
        // Set default payment type to free
        document.getElementById('pt_type').value = 'free';
        handlePaymentTypeChange();
    } else if (type === 'sparring') {
        sparringForm.style.display = 'block';
        // Set default payment type to free
        document.getElementById('sp_type').value = 'free';
        handleSparringPaymentTypeChange();
    }
}

// ========================================
// HANDLE PRIVACY CHANGE (PLAY TOGETHER)
// ========================================
function handlePrivacyChange() {
    const privacy = document.getElementById('pt_privacy').value;
    const communityGroup = document.getElementById('pt_community_group');
    
    if (privacy === 'community') {
        communityGroup.style.display = 'block';
        document.getElementById('pt_community_id').required = true;
    } else {
        communityGroup.style.display = 'none';
        document.getElementById('pt_community_id').required = false;
        document.getElementById('pt_community_id').value = '';
    }
}

// ========================================
// HANDLE PAYMENT TYPE CHANGE (PLAY TOGETHER)
// ========================================
function handlePaymentTypeChange() {
    const paymentType = document.getElementById('pt_type').value;
    const priceGroup = document.getElementById('pt_price_group');
    const paymentTypeGroup = document.getElementById('pt_payment_type_group');
    const calculator = document.getElementById('costCalculator');
    
    if (paymentType === 'paid') {
        priceGroup.style.display = 'block';
        paymentTypeGroup.style.display = 'block';
        document.getElementById('pt_price_per_person').required = true;
        document.getElementById('pt_payment_type').required = true;
        if (selectedSchedulePrice > 0) {
            calculator.style.display = 'block';
            calculateCostPerPerson();
        }
    } else {
        priceGroup.style.display = 'none';
        paymentTypeGroup.style.display = 'none';
        calculator.style.display = 'none';
        document.getElementById('pt_price_per_person').required = false;
        document.getElementById('pt_payment_type').required = false;
        document.getElementById('pt_price_per_person').value = 0;
    }
}

// ========================================
// HANDLE PAYMENT TYPE CHANGE (SPARRING)
// ========================================
function handleSparringPaymentTypeChange() {
    const paymentType = document.getElementById('sp_type').value;
    const costGroup = document.getElementById('sp_cost_group');
    const paymentTypeGroup = document.getElementById('sp_payment_type_group');
    
    if (paymentType === 'paid') {
        costGroup.style.display = 'block';
        paymentTypeGroup.style.display = 'block';
        document.getElementById('sp_cost_per_participant').required = true;
        document.getElementById('sp_payment_type').required = true;
    } else {
        costGroup.style.display = 'none';
        paymentTypeGroup.style.display = 'none';
        document.getElementById('sp_cost_per_participant').required = false;
        document.getElementById('sp_payment_type').required = false;
        document.getElementById('sp_cost_per_participant').value = 0;
    }
}

// ========================================
// CALCULATE COST PER PERSON
// ========================================
function calculateCostPerPerson() {
    const bookingPrice = selectedSchedulePrice;
    const participants = parseInt(document.getElementById('pt_max_participants').value) || 0;
    const pricePerPerson = parseInt(document.getElementById('pt_price_per_person').value) || 0;
    const paymentType = document.getElementById('pt_payment_type') ? document.getElementById('pt_payment_type').value : 'split';
    const ptType = document.getElementById('pt_type').value;
    
    if (bookingPrice > 0 && participants > 0 && ptType === 'paid') {
        let costPerPersonCalc = 0;
        const distributionText = paymentType === 'split' ? 'Dibagi Rata' : 'Dibayar Host';
        
        if (paymentType === 'split') {
            costPerPersonCalc = Math.ceil(bookingPrice / participants) + pricePerPerson;
        } else {
            // Full (dibayar host) - hanya tambahan biaya per orang
            costPerPersonCalc = pricePerPerson;
        }
        
        document.getElementById('calcBookingPrice').textContent = bookingPrice.toLocaleString('id-ID');
        document.getElementById('calcParticipants').textContent = participants;
        document.getElementById('calcPricePerPerson').textContent = pricePerPerson.toLocaleString('id-ID');
        document.getElementById('calcDistribution').textContent = distributionText;
        document.getElementById('calcTotalCost').textContent = costPerPersonCalc.toLocaleString('id-ID');
        document.getElementById('costCalculator').style.display = 'block';
    }
}

// ========================================
// LOAD SECTIONS BY VENUE
// ========================================
function loadSections(venueId) {
    if (!venueId) {
        document.getElementById('sectionGroup').style.display = 'none';
        document.getElementById('scheduleGroup').style.display = 'none';
        return;
    }

    // Reset states
    selectedSectionId = null;
    selectedScheduleId = null;
    selectedSchedulePrice = 0;
    document.getElementById('section_id').value = '';
    document.getElementById('schedule_id').value = '';
    document.getElementById('scheduleInfo').style.display = 'none';
    document.getElementById('scheduleGroup').style.display = 'none';
    document.getElementById('costCalculator').style.display = 'none';
    document.getElementById('btnSubmit').disabled = true;

    // Show loading
    document.getElementById('sectionGroup').style.display = 'block';
    document.getElementById('sectionLoading').style.display = 'block';
    document.getElementById('sectionCards').style.display = 'none';
    document.getElementById('sectionEmpty').style.display = 'none';

    // Fetch sections
    fetch(`/buyer/booking/sections/${venueId}`, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(sections => {
        document.getElementById('sectionLoading').style.display = 'none';
        
        if (sections.length === 0) {
            document.getElementById('sectionEmpty').style.display = 'block';
            return;
        }

        const sectionContainer = document.getElementById('sectionCards');
        sectionContainer.innerHTML = '';
        document.getElementById('sectionCards').style.display = 'grid';

        sections.forEach(section => {
            const card = document.createElement('div');
            card.className = 'schedule-card';
            card.onclick = () => selectSection(section.id, section.section_name);
            card.innerHTML = `
                <div class="schedule-card-header">
                    <div class="schedule-card-title">
                        <i class="fas fa-layer-group"></i>
                        ${section.section_name}
                    </div>
                </div>
                <div class="schedule-card-footer">
                    <span style="font-size: 11px; color: var(--text-light);">
                        ${section.description || 'Tidak ada deskripsi'}
                    </span>
                </div>
            `;
            sectionContainer.appendChild(card);
        });
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('sectionLoading').style.display = 'none';
        document.getElementById('sectionEmpty').style.display = 'block';
        showToast('Gagal memuat section', 'error');
    });
}

// ========================================
// SELECT SECTION
// ========================================
function selectSection(sectionId, sectionName) {
    // Remove previous selection
    document.querySelectorAll('#sectionCards .schedule-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Mark as selected
    event.currentTarget.classList.add('selected');
    selectedSectionId = sectionId;
    document.getElementById('section_id').value = sectionId;
    
    // Reset schedule selection
    selectedScheduleId = null;
    selectedSchedulePrice = 0;
    document.getElementById('schedule_id').value = '';
    document.getElementById('scheduleInfo').style.display = 'none';
    document.getElementById('costCalculator').style.display = 'none';
    document.getElementById('btnSubmit').disabled = true;
    
    // Load schedules for this section
    loadSchedulesBySection(sectionId);
}

// ========================================
// LOAD SCHEDULES BY SECTION
// ========================================
function loadSchedulesBySection(sectionId) {
    // Show schedule group
    document.getElementById('scheduleGroup').style.display = 'block';
    document.getElementById('scheduleLoading').style.display = 'block';
    document.getElementById('scheduleCards').style.display = 'none';
    document.getElementById('scheduleEmpty').style.display = 'none';

    // Fetch schedules
    fetch(`/buyer/booking/schedules-by-section/${sectionId}`, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(schedules => {
        document.getElementById('scheduleLoading').style.display = 'none';
        
        if (schedules.length === 0) {
            document.getElementById('scheduleEmpty').style.display = 'block';
            return;
        }

        const scheduleContainer = document.getElementById('scheduleCards');
        scheduleContainer.innerHTML = '';
        document.getElementById('scheduleCards').style.display = 'grid';

        schedules.forEach(schedule => {
            const card = document.createElement('div');
            card.className = 'schedule-card';
            card.onclick = () => selectSchedule(
                schedule.id,
                schedule.date,
                schedule.start_time,
                schedule.end_time,
                schedule.venue_section_name,
                schedule.rental_price,
                schedule.rental_duration
            );
            
            card.innerHTML = `
                <div class="schedule-card-header">
                    <div class="schedule-card-title">
                        <i class="fas fa-calendar-day"></i>
                        ${schedule.date}
                    </div>
                    <div class="schedule-card-price">
                        Rp ${parseInt(schedule.rental_price).toLocaleString('id-ID')}
                    </div>
                </div>
                <div class="schedule-card-content">
                    <div class="schedule-card-detail">
                        <i class="fas fa-clock"></i>
                        <span><strong>Waktu:</strong> ${schedule.start_time} - ${schedule.end_time}</span>
                    </div>
                    <div class="schedule-card-detail">
                        <i class="fas fa-hourglass-half"></i>
                        <span><strong>Durasi:</strong> ${schedule.rental_duration} jam</span>
                    </div>
                </div>
                <div class="schedule-card-footer">
                    <span style="font-size: 11px; color: var(--text-light);">
                        <i class="fas fa-layer-group"></i> ${schedule.venue_section_name}
                    </span>
                    <span class="schedule-card-badge">Tersedia</span>
                </div>
            `;
            scheduleContainer.appendChild(card);
        });
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('scheduleLoading').style.display = 'none';
        document.getElementById('scheduleEmpty').style.display = 'block';
        showToast('Gagal memuat jadwal', 'error');
    });
}

// ========================================
// SELECT SCHEDULE
// ========================================
function selectSchedule(scheduleId, date, startTime, endTime, sectionName, price, duration) {
    // Remove previous selection
    document.querySelectorAll('#scheduleCards .schedule-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Mark as selected
    event.currentTarget.classList.add('selected');
    selectedScheduleId = scheduleId;
    selectedSchedulePrice = parseInt(price);
    document.getElementById('schedule_id').value = scheduleId;
    
    // Show schedule info
    document.getElementById('scheduleInfo').style.display = 'block';
    document.getElementById('infoDate').textContent = date;
    document.getElementById('infoTime').textContent = `${startTime} - ${endTime}`;
    document.getElementById('infoSection').textContent = sectionName;
    document.getElementById('infoDuration').textContent = duration;
    document.getElementById('infoPrice').textContent = parseInt(price).toLocaleString('id-ID');
    
    // Enable submit button
    document.getElementById('btnSubmit').disabled = false;
    
    // Show cost calculator if play together and paid
    const type = document.getElementById('type').value;
    if (type === 'play_together') {
        const ptType = document.getElementById('pt_type').value;
        if (ptType === 'paid') {
            calculateCostPerPerson();
        }
    }
}

// ========================================
// SUBMIT BOOKING FORM
// ========================================
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('btnSubmit');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    
    fetch('/buyer/booking', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Gagal membuat booking', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat membuat booking', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// ========================================
// SHOW BOOKING DETAIL
// ========================================
function showBookingDetail(bookingId) {
    window.currentBookingId = bookingId;
    
    fetch(`/buyer/booking/${bookingId}`, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const booking = data.booking;
            const detailContent = document.getElementById('detailContent');
            
            let additionalInfo = '';
            
            // Play Together Info
            if (booking.play_together) {
                const pt = booking.play_together;
                additionalInfo += `
                    <div class="additional-info-section">
                        <h4><i class="fas fa-users"></i> Informasi Play Together</h4>
                        <div class="schedule-details">
                            <div><strong>Privacy:</strong> <span>${pt.privacy}</span></div>
                            ${pt.community ? `<div><strong>Community:</strong> <span>${pt.community.name}</span></div>` : ''}
                            <div><strong>Max Partisipan:</strong> <span>${pt.max_participants} orang</span></div>
                            <div><strong>Tipe:</strong> <span>${pt.type === 'paid' ? 'Berbayar' : 'Gratis'}</span></div>
                            ${pt.type === 'paid' && pt.payment_type ? `<div><strong>Distribusi:</strong> <span>${pt.payment_type === 'split' ? 'Dibagi Rata' : 'Dibayar Host'}</span></div>` : ''}
                            ${pt.type === 'paid' ? `<div><strong>Harga/Orang:</strong> <span>Rp ${parseInt(pt.price_per_person).toLocaleString('id-ID')}</span></div>` : ''}
                            ${pt.gender ? `<div><strong>Gender:</strong> <span>${pt.gender}</span></div>` : ''}
                            <div><strong>Perlu Approval:</strong> <span>${pt.host_approval ? 'Ya' : 'Tidak'}</span></div>
                            ${pt.description ? `<div><strong>Deskripsi:</strong> <span>${pt.description}</span></div>` : ''}
                        </div>
                    </div>
                `;
            }
            
            // Sparring Info
            if (booking.sparring) {
                const sp = booking.sparring;
                additionalInfo += `
                    <div class="additional-info-section">
                        <h4><i class="fas fa-trophy"></i> Informasi Sparring</h4>
                        <div class="schedule-details">
                            <div><strong>Privacy:</strong> <span>${sp.privacy}</span></div>
                            <div><strong>Tipe Partisipan:</strong> <span>${sp.participant_type}</span></div>
                            <div><strong>Tipe:</strong> <span>${sp.type === 'paid' ? 'Berbayar' : 'Gratis'}</span></div>
                            ${sp.type === 'paid' && sp.payment_type ? `<div><strong>Distribusi:</strong> <span>${sp.payment_type === 'split' ? 'Dibagi Rata' : 'Dibayar Host'}</span></div>` : ''}
                            ${sp.type === 'paid' ? `<div><strong>Biaya/Partisipan:</strong> <span>Rp ${parseInt(sp.cost_per_participant).toLocaleString('id-ID')}</span></div>` : ''}
                            <div><strong>Perlu Approval:</strong> <span>${sp.host_approval ? 'Ya' : 'Tidak'}</span></div>
                            ${sp.description ? `<div><strong>Deskripsi:</strong> <span>${sp.description}</span></div>` : ''}
                        </div>
                    </div>
                `;
            }
            
            detailContent.innerHTML = `
                <div class="schedule-info">
                    <h4><i class="fas fa-info-circle"></i> Informasi Booking</h4>
                    <div class="schedule-details">
                        <div><strong>Kode Tiket:</strong> <span style="font-weight: 800; color: var(--primary);">${booking.ticket_code}</span></div>
                        <div><strong>Tipe:</strong> <span>${booking.type}</span></div>
                        <div><strong>Venue:</strong> <span>${booking.venue.venue_name}</span></div>
                        <div><strong>Lokasi:</strong> <span>${booking.venue.location}</span></div>
                        <div><strong>Section:</strong> <span>${booking.schedule.venue_section?.section_name || 'N/A'}</span></div>
                        <div><strong>Tanggal:</strong> <span>${booking.schedule.formatted_date}</span></div>
                        <div><strong>Waktu:</strong> <span>${booking.schedule.formatted_time_range}</span></div>
                        <div><strong>Durasi:</strong> <span>${booking.schedule.rental_duration} jam</span></div>
                        <div><strong>Harga:</strong> <span>Rp ${parseInt(booking.schedule.rental_price).toLocaleString('id-ID')}</span></div>
                        <div><strong>Status:</strong> 
                            <span class="status-badge badge-${booking.status}">
                                <i class="fas ${booking.status === 'confirmed' ? 'fa-check-circle' : booking.status === 'pending' ? 'fa-clock' : 'fa-times-circle'}"></i>
                                ${booking.status.toUpperCase()}
                            </span>
                        </div>
                        <div><strong>Status Pembayaran:</strong> 
                            <span class="status-badge badge-${booking.payment_status}">
                                <i class="fas ${booking.payment_status === 'paid' ? 'fa-check-circle' : 'fa-clock'}"></i>
                                ${booking.payment_status.toUpperCase()}
                            </span>
                        </div>
                    </div>
                </div>
                ${additionalInfo}
            `;
            
            document.getElementById('modalDetail').style.display = 'flex';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Gagal memuat detail booking', 'error');
    });
}

// ========================================
// DELETE BOOKING
// ========================================
function deleteBooking(bookingId, ticketCode) {
    document.getElementById('modalHapus').style.display = 'flex';
    document.getElementById('hapusBookingCode').textContent = ticketCode;
    
    document.getElementById('btnConfirmHapus').onclick = function() {
        fetch(`/buyer/booking/${bookingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closeModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Gagal menghapus booking', 'error');
            }
        })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
    };
}

// ========================================
// SEARCH BOOKING
// ========================================
function searchBooking() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.booking-card');
    
    cards.forEach(card => {
        const bookingCode = card.getAttribute('data-booking-code');
        if (bookingCode.includes(searchTerm)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// ========================================
// FILTER BY STATUS
// ========================================
function filterByStatus(status) {
    const cards = document.querySelectorAll('.booking-card');
    
    cards.forEach(card => {
        const cardStatus = card.getAttribute('data-status');
        if (status === 'all' || cardStatus === status) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// ========================================
// TOAST NOTIFICATION
// ========================================
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = toast.querySelector('i');
    
    if (type === 'success') {
        icon.className = 'fas fa-check-circle';
        icon.style.color = 'var(--success)';
    } else if (type === 'error') {
        icon.className = 'fas fa-exclamation-circle';
        icon.style.color = 'var(--danger)';
    } else if (type === 'warning') {
        icon.className = 'fas fa-exclamation-triangle';
        icon.style.color = 'var(--warning)';
    } else if (type === 'info') {
        icon.className = 'fas fa-info-circle';
        icon.style.color = 'var(--info)';
    }
    
    toast.querySelector('span').textContent = message;
    toast.className = `toast show ${type}`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

function showNotification() {
    showToast('Tidak ada notifikasi baru', 'info');
}

// ========================================
// INITIALIZE ON PAGE LOAD
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Auto-load on page load (if venueId exists)
    const urlParams = new URLSearchParams(window.location.search);
    const venueId = urlParams.get('venue_id');
    
    if (venueId) {
        openModalTambah(venueId);
    }
});
<script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const payBtn = document.getElementById('btnPayBooking');

    if (!payBtn) return;

    payBtn.addEventListener('click', function () {

        if (!window.currentBookingId) {
            alert('Booking tidak valid');
            return;
        }

        fetch(`/buyer/booking/${window.currentBookingId}/pay`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert(res.message);
                return;
            }

            snap.pay(res.snap_token, {
                onSuccess: function () {
                    alert('Pembayaran berhasil');
                    location.reload();
                },
                onPending: function () {
                    alert('Menunggu pembayaran');
                },
                onError: function () {
                    alert('Pembayaran gagal');
                }
            });
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan');
        });
    });
});
</script>