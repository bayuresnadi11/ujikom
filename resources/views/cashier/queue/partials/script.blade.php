<script>
// Global variables
let selectedVenueId = null;
let selectedSectionId = null;
let allSections = [];
let selectedDate = null;
let allSchedules = [];
let cart = [];
let selectedBuyer = null;
let currentVenueName = '';
let currentSectionName = '';
let selectedPaymentMethod = null;
let searchTimeout = null;

// ========= INITIALIZATION =========
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Page loaded');
    
    // Hide cash section by default
    const cashSection = document.getElementById('cashSection');
    if (cashSection) {
        cashSection.style.display = 'none';
    }
    
    // Initialize
    loadVenues();
    initializeEventListeners();
    
    // DEBUG: Force check button state after 1 second
    setTimeout(function() {
        console.log('🔍 Checking pay button status...');
        const btn = document.getElementById('payButton');
        if (btn) {
            console.log('Button found:', btn);
            console.log('Button disabled:', btn.disabled);
            console.log('Button classes:', btn.className);
            // Optional: Force enable for testing if needed
            // btn.disabled = false; 
        } else {
            console.error('❌ Pay button NOT found in DOM!');
        }
    }, 1000);
});

function initializeEventListeners() {
    console.log('🔌 Initializing event listeners...');
    
    // Payment method radio buttons
    const cashRadio = document.getElementById('cash');
    const midtransRadio = document.getElementById('midtrans');
    const cashInput = document.getElementById('cashInput');
    const cashSection = document.getElementById('cashSection');
    
    if (cashRadio) {
        console.log('✅ Cash radio found');
        cashRadio.addEventListener('change', function() {
            if (this.checked) {
                selectedPaymentMethod = 'cash';
                cashSection.style.display = 'block';
                cashInput.value = '';
                calculateChange();
                updatePayButtonState();
                console.log('✅ Cash payment selected');
            }
        });
    } else { console.warn('⚠️ Cash radio NOT found'); }
    
    if (midtransRadio) {
        console.log('✅ Midtrans radio found');
        midtransRadio.addEventListener('change', function() {
            if (this.checked) {
                selectedPaymentMethod = 'midtrans';
                cashSection.style.display = 'none';
                cashInput.value = '';
                document.getElementById('changeAmount').textContent = '-';
                updatePayButtonState();
                console.log('✅ Midtrans payment selected');
            }
        });
    } else { console.warn('⚠️ Midtrans radio NOT found'); }
    
    // Buyer search autocomplete
    const buyerSearch = document.getElementById('buyerSearch');
    const buyerDropdown = document.getElementById('buyerDropdown');
    
    if (buyerSearch) {
        console.log('✅ Buyer search input found');
        buyerSearch.addEventListener('input', function() {
            const keyword = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (keyword.length < 2) {
                buyerDropdown.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchBuyers(keyword);
            }, 300);
        });
    } else { console.warn('⚠️ Buyer search input NOT found'); }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            if (buyerDropdown) {
                buyerDropdown.style.display = 'none';
            }
        }
    });
    
    // Cash input listener
    if (cashInput) {
        console.log('✅ Cash input found');
        cashInput.addEventListener('input', function() {
            calculateChange();
            updatePayButtonState();
        });
    } else { console.warn('⚠️ Cash input NOT found'); }
    
    // Pay button listener
    const payButton = document.getElementById('payButton');
    if (payButton) {
        console.log('✅ Pay button found, attaching click listener');
        // Remove old listeners (clone node trick if needed, but simple add is fine usually)
        payButton.onclick = function(e) { // Force override onclick to be sure
            console.log('🖱️ Pay button clicked!');
            e.preventDefault(); // Prevent form submission if inside form
            processPayment(); 
        };
    } else {
        console.error('❌ Pay button NOT found in initializeEventListeners');
    }
    
    // Category and search listeners
    const categorySelect = document.getElementById('category');
    const searchInput = document.getElementById('search');
    
    if (categorySelect) {
        categorySelect.addEventListener('change', loadVenues);
    }
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(window.searchTimer);
            window.searchTimer = setTimeout(loadVenues, 500);
        });
    }
}
function loadVenues() {
    const category = document.getElementById('category').value;
    const keyword = document.getElementById('search').value;
    const container = document.getElementById('venue-list');

    container.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Memuat data lapangan...</p>
        </div>
    `;

    const url = `/cashier/venues/search?category_id=${category}&keyword=${keyword}`;

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('✅ Data received:', data);
            container.innerHTML = '';

            if (!data || data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-1">Tidak ada lapangan ditemukan</p>
                        <small class="text-muted">Coba ubah filter atau kata kunci pencarian</small>
                    </div>
                `;
                return;
            }

            // Load template
            const template = document.getElementById('venueCardTemplate').innerHTML;
            container.innerHTML = '';

            data.forEach(venue => {
                const categoryName = venue.category ? venue.category.category_name : 'Umum';
                const photoUrl = venue.photo 
                    ? `/storage/${venue.photo}`
                    : 'https://via.placeholder.com/300x200/41a67e/ffffff?text=No+Image';
                
                const price = venue.starting_price ? 
                    `Rp ${Number(venue.starting_price).toLocaleString('id-ID')}` : 
                    'Rp 0';
                
                const location = venue.location || 'Lokasi tidak tersedia';
                const availableCount = venue.available_schedules || 0;

                let cardHtml = template
                    .replace(/{venueId}/g, venue.id)
                    .replace(/{venueName}/g, venue.venue_name.replace(/'/g, "\\'"))
                    .replace(/{categoryName}/g, categoryName.replace(/'/g, "\\'"))
                    .replace(/{imageUrl}/g, photoUrl)
                    .replace(/{location}/g, location.replace(/'/g, "\\'"))
                    .replace(/{availableCount}/g, availableCount);

                container.innerHTML += cardHtml;
            });
        })
        .catch(error => {
            console.error('❌ Error:', error);
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fa-solid fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger mb-1">Gagal memuat data lapangan</p>
                    <small class="text-muted">${error.message}</small>
                    <br>
                    <button class="btn btn-sm btn-outline-primary mt-3" onclick="loadVenues()">
                        <i class="fa-solid fa-rotate-right me-1"></i>Coba Lagi
                    </button>
                </div>
            `;
        });
}

function selectVenue(venueId, venueName, categoryName) {
    console.log('✅ Venue selected:', { venueId, venueName });
    
    selectedVenueId = venueId;
    currentVenueName = venueName;
    document.getElementById('sheetVenueName').textContent = venueName;
    document.getElementById('sheetVenueMeta').textContent = categoryName;
    
    openBottomSheet();
    loadSections(venueId);
}

function openBottomSheet() {
    document.getElementById('bottomSheetOverlay').classList.add('show');
    document.getElementById('bottomSheet').classList.add('show');
    document.body.style.overflow = 'hidden';
    
    if (selectedSectionId) {
        renderSchedules();
    }
}

function closeBottomSheet() {
    document.getElementById('bottomSheetOverlay').classList.remove('show');
    document.getElementById('bottomSheet').classList.remove('show');
    document.body.style.overflow = '';
}

function loadSections(venueId) {
    const tabsContainer = document.getElementById('sectionTabs');
    
    tabsContainer.innerHTML = `
        <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="text-muted ms-2">Memuat lapangan...</span>
    `;
    
    fetch(`/cashier/venues/${venueId}/sections`)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('✅ Sections loaded:', data);
            allSections = data;
            tabsContainer.innerHTML = '';
            
            if (!data || data.length === 0) {
                tabsContainer.innerHTML = `
                    <div class="alert alert-warning w-100 mb-0">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        Belum ada bagian lapangan untuk venue ini
                    </div>
                `;
                document.getElementById('scheduleList').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada lapangan tersedia</p>
                    </div>
                `;
                return;
            }
            
            data.forEach((section, index) => {
                const isFirst = index === 0;
                tabsContainer.innerHTML += `
                    <span class="section-tab ${isFirst ? 'active' : ''}" 
                          onclick="selectSection(${section.id}, '${section.section_name.replace(/'/g, "\\'")}', '${section.description || ''}', this)">
                        <i class="fa-solid fa-futbol me-1"></i>
                        ${section.section_name}
                    </span>
                `;
            });
            
            if (data.length > 0) {
                const firstSection = data[0];
                const firstTab = tabsContainer.querySelector('.section-tab');
                selectSection(firstSection.id, firstSection.section_name, firstSection.description || '', firstTab);
            }
        })
        .catch(error => {
            console.error('❌ Error loading sections:', error);
            tabsContainer.innerHTML = `
                <div class="alert alert-danger w-100 mb-0">
                    Gagal memuat lapangan: ${error.message}
                </div>
            `;
        });
}

function selectSection(sectionId, sectionName, sectionDesc, element) {
    console.log('✅ Section selected:', { sectionId, sectionName });
    
    selectedSectionId = sectionId;
    currentSectionName = sectionName;
    
    document.querySelectorAll('.section-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    if (element) {
        element.classList.add('active');
    }
    
    document.getElementById('fieldHeader').style.display = 'flex';
    document.getElementById('currentSectionName').textContent = sectionName;
    document.getElementById('currentSectionDesc').textContent = sectionDesc || 'Tidak ada deskripsi';
    
    generateDateButtons();
    loadSchedules(sectionId);
}

function generateDateButtons() {
    const dateFilter = document.getElementById('dateFilter');
    const dateButtons = document.getElementById('dateButtons');
    
    dateFilter.style.display = 'block';
    dateButtons.innerHTML = '';
    
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const today = new Date();
    
    for (let i = 0; i < 7; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        
        let dayLabel = '';
        if (i === 0) dayLabel = 'Hari Ini';
        else if (i === 1) dayLabel = 'Besok';
        else if (i === 2) dayLabel = 'Lusa';
        else dayLabel = dayNames[date.getDay()];
        
        const dateStr = date.toISOString().split('T')[0];
        const dateLabel = `${date.getDate()}/${date.getMonth() + 1}`;
        
        const isFirst = i === 0;
        dateButtons.innerHTML += `
            <div class="date-btn ${isFirst ? 'active' : ''}" onclick="selectDate('${dateStr}', this)">
                <div class="day-label">${dayLabel}</div>
                <div class="date-label">${dateLabel}</div>
            </div>
        `;
    }
    
    selectedDate = today.toISOString().split('T')[0];
}

function selectDate(dateStr, element) {
    console.log('✅ Date selected:', dateStr);
    
    selectedDate = dateStr;
    
    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (element) {
        element.classList.add('active');
    }
    
    if (selectedSectionId) {
        loadSchedules(selectedSectionId);
    }
}

function loadSchedules(sectionId) {
    const container = document.getElementById('scheduleList');
    
    container.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Memuat jadwal...</p>
        </div>
    `;
    
    fetch(`/cashier/sections/${sectionId}/schedules`)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('✅ Schedules loaded:', data);
            allSchedules = data;
            renderSchedules();
        })
        .catch(error => {
            console.error('❌ Error loading schedules:', error);
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fa-solid fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger mb-1">Gagal memuat jadwal</p>
                    <small class="text-muted">${error.message}</small>
                    <br>
                    <button class="btn btn-sm btn-outline-primary mt-3" onclick="loadSchedules(${sectionId})">
                        <i class="fa-solid fa-rotate-right me-1"></i>Coba Lagi
                    </button>
                </div>
            `;
        });
}

function renderSchedules() {
    const container = document.getElementById('scheduleList');
    container.innerHTML = '';

    console.log('🔍 All schedules:', allSchedules);
    console.log('🔍 Selected date:', selectedDate);

    if (!Array.isArray(allSchedules) || allSchedules.length === 0) {
        showEmptyState(container);
        return;
    }

    const now = new Date();
    const normalizedSelectedDate = normalizeDate(selectedDate);

    const filteredSchedules = allSchedules.filter(schedule => {
        // kalau ada field date → filter by tanggal
        if (schedule.date) {
            const scheduleDate = normalizeDate(schedule.date);
            if (scheduleDate !== normalizedSelectedDate) return false;

            // 🔥 filter jam yang sudah lewat (khusus hari ini)
            const scheduleEnd = new Date(`${scheduleDate} ${schedule.end_time}`);
            return scheduleEnd > now;
        }

        // kalau ga ada field date → tetap tampil
        return true;
    });

    if (filteredSchedules.length === 0) {
        showEmptyState(container);
        return;
    }

    container.innerHTML = '<div class="schedule-grid"></div>';
    const grid = container.querySelector('.schedule-grid');

    filteredSchedules.forEach(schedule => {
        const status = schedule.available ? 'available' : 'occupied';
        const statusText = schedule.available ? 'Tersedia' : 'Terisi';
        const statusIcon = schedule.available ? 'check-circle' : 'times-circle';
        const isInCart = cart.some(item => item.schedule.id === schedule.id);
        const displayDate = schedule.date || selectedDate;

        grid.innerHTML += `
            <div class="schedule-card ${status} ${isInCart ? 'selected' : ''}"
                 onclick="selectSchedule(${schedule.id})">

                ${isInCart ? `
                    <div class="selected-badge">
                        <i class="fa-solid fa-check"></i>
                        <span>Ditambahkan</span>
                    </div>
                ` : ''}

                <div class="schedule-date">
                    <i class="fa-solid fa-calendar-day me-1"></i>
                    ${formatDate(displayDate)}
                </div>

                <div class="schedule-time">
                    <i class="fa-solid fa-clock me-1"></i>
                    ${schedule.start_time} - ${schedule.end_time}
                </div>

                <div class="schedule-status ${status}">
                    <i class="fa-solid fa-${statusIcon} me-1"></i>
                    ${statusText}
                </div>

                <div class="schedule-meta">
                    <i class="fa-solid fa-hourglass-half me-1"></i>
                    ${schedule.rental_duration} jam
                </div>

                <div class="schedule-price">
                    Rp ${Number(schedule.rental_price).toLocaleString('id-ID')}
                </div>
            </div>
        `;
    });
}

/* ================= HELPER ================= */

function showEmptyState(container) {
    container.innerHTML = `
        <div class="text-center py-5">
            <i class="fa-solid fa-calendar-xmark fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-1">Belum ada jadwal tersedia</p>
            <small class="text-muted">
                Untuk tanggal ${formatDate(selectedDate)}
            </small>
        </div>
    `;
}

function normalizeDate(dateStr) {
    if (!dateStr) return '';
    
    const date = new Date(dateStr);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    
    return `${dayNames[date.getDay()]}, ${date.getDate()} ${monthNames[date.getMonth()]}`;
}

function selectSchedule(scheduleId) {
    const schedule = allSchedules.find(s => s.id === scheduleId);
    
    if (!schedule || !schedule.available) {
        return;
    }
    
    const existingItem = cart.find(item => item.schedule.id === scheduleId);
    if (existingItem) {
        return;
    }
    
    cart.push({
        schedule: schedule,
        venueName: currentVenueName,
        sectionName: currentSectionName
    });
    
    console.log('🛒 Cart updated:', cart);
    renderCart();
    renderSchedules();
}

function renderCart() {
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fa-solid fa-cart-shopping fa-2x mb-2"></i>
                <p class="small mb-0">Belum ada pesanan</p>
            </div>
        `;
        cartCount.textContent = '0 item';
        updateTotal();
        return;
    }
    
    cartCount.textContent = `${cart.length} item`;
    cartItems.innerHTML = '';
    
    cart.forEach((item, index) => {
        const schedule = item.schedule;
        const displayDate = schedule.date ? schedule.date : selectedDate;
        
        cartItems.innerHTML += `
            <div class="cart-item">
                <div class="cart-item-header">
                    <div>
                        <div class="cart-item-venue">${item.venueName}</div>
                        <div class="cart-item-section">${item.sectionName}</div>
                    </div>
                    <button class="remove-item" onclick="removeFromCart(${index})" title="Hapus dari keranjang">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="cart-item-time">
                    <i class="fa-solid fa-calendar me-1"></i>${formatDate(displayDate)}
                </div>
                <div class="cart-item-time">
                    <i class="fa-solid fa-clock me-1"></i>${schedule.start_time} - ${schedule.end_time}
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="badge bg-light text-dark">
                        <i class="fa-solid fa-hourglass-half me-1"></i>${schedule.rental_duration} jam
                    </span>
                    <div class="cart-item-price">Rp ${Number(schedule.rental_price).toLocaleString('id-ID')}</div>
                </div>
            </div>
        `;
    });
    
    updateTotal();
    updatePayButtonState();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
    renderSchedules();
}

function updateTotal() {
    const total = cart.reduce((sum, item) => sum + item.schedule.rental_price, 0);
    document.getElementById('subtotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('total').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    calculateChange();
    updatePayButtonState();
}

// ========= PAYMENT FUNCTIONS =========

function calculateChange() {
    const total = cart.reduce((sum, item) => sum + item.schedule.rental_price, 0);
    const cashInput = document.getElementById('cashInput');
    const changeAmount = document.getElementById('changeAmount');
    
    if (selectedPaymentMethod !== 'cash') {
        changeAmount.textContent = '-';
        return;
    }
    
    const cash = parseFloat(cashInput.value) || 0;
    const change = cash - total;
    
    if (cash >= total && cash > 0) {
        changeAmount.textContent = `Rp ${change.toLocaleString('id-ID')}`;
        changeAmount.classList.remove('text-danger');
        changeAmount.classList.add('text-success');
    } else if (cash > 0) {
        changeAmount.textContent = `Kurang Rp ${Math.abs(change).toLocaleString('id-ID')}`;
        changeAmount.classList.remove('text-success');
        changeAmount.classList.add('text-danger');
    } else {
        changeAmount.textContent = 'Rp 0';
        changeAmount.classList.remove('text-success', 'text-danger');
    }
}

function updatePayButtonState() {
    const total = cart.reduce((sum, item) => sum + item.schedule.rental_price, 0);
    const payButton = document.getElementById('payButton');
    
    console.log('🔍 Checking pay button state:', {
        cartLength: cart.length,
        selectedBuyer: selectedBuyer,
        selectedPaymentMethod: selectedPaymentMethod,
        total: total
    });
    
    // Check if cart is empty
    if (cart.length === 0) {
        payButton.disabled = true;
        console.log('❌ Button disabled: No cart items');
        return;
    }
    
    // Check if payment method is selected
    if (!selectedPaymentMethod) {
        payButton.disabled = true;
        console.log('❌ Button disabled: No payment method selected');
        return;
    }
    
    // Special check for Cash payment
    if (selectedPaymentMethod === 'cash') {
        const cash = parseFloat(document.getElementById('cashInput').value) || 0;
        const isEnabled = cash >= total && cash > 0;
        payButton.disabled = !isEnabled;
        
        // Update button text to indicate why if disabled
        if (!isEnabled) {
            payButton.innerHTML = '<i class="fa-solid fa-ban me-2"></i>Uang Kurang';
        } else {
            payButton.innerHTML = '<i class="fa-solid fa-print me-2"></i>Bayar & Cetak Struk';
        }
        
        console.log(isEnabled ? '✅ Button enabled (Cash)' : '❌ Button disabled: Cash not enough', { cash, total });
    } else {
        // For Midtrans, always enable if cart has items
        payButton.disabled = false;
        payButton.innerHTML = '<i class="fa-solid fa-credit-card me-2"></i>Bayar & Cetak Struk';
        console.log('✅ Button enabled (Midtrans)');
    }
}

// ========= PAYMENT FUNCTIONS =========

function processPayment() {
    const total = cart.reduce((sum, item) => sum + item.schedule.rental_price, 0);
    
    if (!selectedBuyer) {
        showErrorModal('Penyewa Belum Dipilih', 'Silakan pilih nama penyewa terlebih dahulu');
        return;
    }
    
    if (cart.length === 0) {
        showErrorModal('Keranjang Kosong', 'Tambahkan jadwal ke keranjang terlebih dahulu');
        return;
    }
    
    if (!selectedPaymentMethod) {
        showErrorModal('Metode Pembayaran Belum Dipilih', 'Silakan pilih metode pembayaran (Cash atau Midtrans)');
        return;
    }
    
    if (selectedPaymentMethod === 'cash') {
        const cash = parseFloat(document.getElementById('cashInput').value) || 0;
        
        if (cash < total) {
            const kurang = total - cash;
            showErrorModal('Uang Tidak Cukup', `Kurang Rp ${kurang.toLocaleString('id-ID')}`);
            return;
        }
        
        showPaymentModal(total, cash);
    } else {
        showPaymentModal(total, null);
    }
}

function showPaymentModal(total, cash) {
    const community = document.getElementById('community').value || '-';
    
    document.getElementById('modalBuyerName').textContent = selectedBuyer ? selectedBuyer.name : '-';
    document.getElementById('modalBuyerPhone').textContent = selectedBuyer ? selectedBuyer.phone : '-';
    document.getElementById('modalCommunity').textContent = community;
    
    document.getElementById('modalTotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('modalItemCount').textContent = `${cart.length} item`;
    
    if (selectedPaymentMethod === 'cash') {
        const change = cash - total;
        document.getElementById('modalCash').textContent = `Rp ${cash.toLocaleString('id-ID')}`;
        document.getElementById('modalChange').textContent = `Rp ${change.toLocaleString('id-ID')}`;
        
        document.querySelector('.summary-cash').style.display = 'block';
        document.querySelector('.summary-change').style.display = 'block';
    } else {
        document.querySelector('.summary-cash').style.display = 'none';
        document.querySelector('.summary-change').style.display = 'none';
    }
    
    updateModalOrderDetails();
    
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    paymentModal.show();
}

function updateModalOrderDetails() {
    const container = document.getElementById('modalOrderDetails');
    container.innerHTML = '';
    
    cart.forEach((item, index) => {
        const schedule = item.schedule;
        const displayDate = schedule.date ? schedule.date : selectedDate;
        
        container.innerHTML += `
            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                <div>
                    <div class="fw-semibold">${item.venueName} - ${item.sectionName}</div>
                    <small class="text-muted">
                        <i class="fa-solid fa-calendar me-1"></i> ${formatDate(displayDate)} • 
                        <i class="fa-solid fa-clock me-1"></i> ${schedule.start_time} - ${schedule.end_time}
                    </small>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">Rp ${Number(schedule.rental_price).toLocaleString('id-ID')}</div>
                    <small class="text-muted">${schedule.rental_duration} Jam</small>
                </div>
            </div>
        `;
    });
}

function confirmPayment() {
    const total = cart.reduce((sum, item) => sum + item.schedule.rental_price, 0);
    const community = document.getElementById('community').value;
    
    let paymentData = {
        buyer_id: selectedBuyer.id,
        community: community,
        method: selectedPaymentMethod,
        amount: total,
        schedules: cart.map(item => ({
            schedule_id: item.schedule.id,
            venue_name: item.venueName,
            section_name: item.sectionName,
            start_time: item.schedule.start_time,
            end_time: item.schedule.end_time,
            rental_duration: item.schedule.rental_duration,
            rental_price: item.schedule.rental_price,
            date: item.schedule.date || selectedDate
        }))
    };
    
    if (selectedPaymentMethod === 'cash') {
        const cash = parseFloat(document.getElementById('cashInput').value);
        const change = cash - total;
        
        paymentData.paid_amount = cash;
        paymentData.change = change > 0 ? change : 0;
    } else {
        paymentData.paid_amount = null;
        paymentData.change = null;
    }
    
    console.log('💳 Processing payment:', paymentData);
    
    const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
    paymentModal.hide();
    
    // Tampilkan loading
    showLoading('Memproses pembayaran...');
    
    fetch('/cashier/process-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(paymentData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        hideLoading();
        
        if (data.success) {
            if (selectedPaymentMethod === 'midtrans' && data.snap_token) {
                // Midtrans payment
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        
                        // Update payment status di backend
                        updatePaymentStatus(data.order_id, 'success', data.transaction_code);
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        updatePaymentStatus(data.order_id, 'pending', data.transaction_code);
                        showErrorModal('Pembayaran Pending', 'Silakan selesaikan pembayaran Anda. Struk akan dicetak setelah pembayaran sukses.');
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        updatePaymentStatus(data.order_id, 'failed', data.transaction_code);
                        showErrorModal('Pembayaran Gagal', 'Terjadi kesalahan saat memproses pembayaran');
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        // Optional: show message that payment was cancelled
                        showErrorModal('Pembayaran Dibatalkan', 'Anda menutup popup pembayaran.');
                    }
                });
            } else {
                // Cash payment langsung sukses
                handlePaymentSuccess(data.transaction_code || data.order_id || data.booking_ids[0], data.booking_ids);
            }
        } else {
            showErrorModal('Pembayaran Gagal', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Payment error:', error);
        showErrorModal('Terjadi Kesalahan', 'Gagal memproses pembayaran: ' + error.message);
    });
}

function updatePaymentStatus(orderId, status, transactionCode = null) {
    // Tampilkan loading saat update status
    showLoading('Memperbarui status pembayaran...');
    
    fetch('/cashier/payment/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            order_id: orderId,
            status: status,
            transaction_code: transactionCode
        })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        hideLoading();
        
        if (data.success) {
            if (status === 'success') {
                // Redirect ke struk untuk pembayaran sukses
                const receiptCode = data.transaction_code || orderId;
                setTimeout(() => {
                    handlePaymentSuccess(receiptCode, data.booking_ids || []);
                }, 1000);
            } else if (status === 'pending') {
                showErrorModal('Pembayaran Pending', 'Silakan selesaikan pembayaran Anda. Struk akan dicetak setelah pembayaran sukses.');
                // Reset form untuk pending payment
                resetCartAndForm();
            } else {
                showErrorModal('Pembayaran Gagal', 'Pembayaran tidak berhasil. Silakan coba lagi.');
                resetCartAndForm();
            }
        } else {
            showErrorModal('Update Gagal', data.message || 'Gagal memperbarui status pembayaran');
            resetCartAndForm();
        }
    })
    .catch(err => {
        hideLoading();
        console.error('Update status error:', err);
        
        // Fallback: jika update status error tapi payment sukses, tetap coba buka struk
        if (status === 'success') {
            showErrorModal('Warning', 'Pembayaran sukses, tetapi ada masalah update status. Mencoba mencetak struk...');
            setTimeout(() => {
                handlePaymentSuccess(transactionCode || orderId, []);
            }, 1000);
        } else {
            showErrorModal('Error', 'Gagal memperbarui status pembayaran: ' + err.message);
            resetCartAndForm();
        }
    });
}

function handlePaymentSuccess(transactionCode, bookingIds) {
    console.log('✅ Payment success, opening receipt:', transactionCode);
    
    // Validasi transaction code
    if (!transactionCode || transactionCode === 'undefined') {
        console.error('Invalid transaction code:', transactionCode);
        showErrorModal('Error', 'Transaction code tidak valid. Silakan cek Riwayat Tiket untuk mencetak struk.');
        resetCartAndForm();
        return;
    }
    
    // URL Receipt - FIX 404 Error (Use correct route prefix)
    const receiptUrl = `/cashier/receipt/${transactionCode}?autoprint=true`; 

    // SILENT PRINT using Wrapper Iframe
    try {
        // Remove old frame if exists
        const oldFrame = document.getElementById('receipt-print-frame');
        if (oldFrame) document.body.removeChild(oldFrame);
        
        // Create new hidden iframe
        const iframe = document.createElement('iframe');
        iframe.id = 'receipt-print-frame';
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';
        iframe.src = receiptUrl;
        
        document.body.appendChild(iframe);
        console.log('🖨️ Printing via hidden iframe...');
    } catch (e) {
        console.error('Print error:', e);
    }
    
    // Reset cart dan form
    resetCartAndForm();
    
    // Tampilkan modal sukses
    const successMessage = bookingIds && bookingIds.length > 0 
        ? `Pembayaran berhasil! ${bookingIds.length} booking telah dibuat.`
        : 'Pembayaran berhasil!';
    
    const successModalBody = document.querySelector('#successModal .modal-body');
    const originalContent = successModalBody.innerHTML;
    
    // Update content modal
    document.getElementById('successMessage').textContent = successMessage;
    
    // Tambahkan tombol manual print ke modal
    const manualPrintBtn = document.createElement('div');
    manualPrintBtn.className = 'mt-3';
    manualPrintBtn.innerHTML = `
        <a href="${receiptUrl}" target="_blank" class="btn btn-outline-success btn-sm">
            <i class="fa-solid fa-print me-1"></i> Cetak Struk Manual
        </a>
    `;
    document.getElementById('successMessage').appendChild(manualPrintBtn);
    
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    
    // Auto close modal setelah 4 detik (agak lama biar sempat lihat tombol print manual)
    setTimeout(() => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
        if (modal) {
            modal.hide();
            // Restore original content (remove manual print button for next time)
            // Actually simpler to just not restore, or handle it in show
             // Clean up extra buttons
             if (manualPrintBtn.parentNode) {
                 manualPrintBtn.parentNode.removeChild(manualPrintBtn);
             }
        }
        
        // Refresh schedules jika ada section yang dipilih
        if (selectedSectionId) {
            loadSchedules(selectedSectionId);
        }
        
        // Refresh venue list juga
        loadVenues();
        
    }, 4000);
}

function resetCartAndForm() {
    console.log('🔄 Resetting cart and form');
    
    // Reset cart
    cart = [];
    renderCart();
    
    // Reset buyer selection
    clearBuyer();
    document.getElementById('community').value = '';
    
    // Reset payment form
    document.getElementById('cashInput').value = '';
    const cashRadio = document.getElementById('cash');
    const midtransRadio = document.getElementById('midtrans');
    
    if (cashRadio) cashRadio.checked = false;
    if (midtransRadio) midtransRadio.checked = false;
    
    selectedPaymentMethod = null;
    document.getElementById('cashSection').style.display = 'none';
    document.getElementById('changeAmount').textContent = '-';
    
    // Reset total display
    document.getElementById('subtotal').textContent = 'Rp 0';
    document.getElementById('total').textContent = 'Rp 0';
    
    // Close bottom sheet jika terbuka
    closeBottomSheet();
    
    // Reset selected venue dan section
    selectedVenueId = null;
    selectedSectionId = null;
    currentVenueName = '';
    currentSectionName = '';
    
    console.log('✅ Cart and form reset complete');
}

// Helper functions untuk loading
function showLoading(message = 'Memproses...') {
    let loadingEl = document.getElementById('loadingOverlay');
    if (!loadingEl) {
        loadingEl = document.createElement('div');
        loadingEl.id = 'loadingOverlay';
        loadingEl.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        `;
        document.body.appendChild(loadingEl);
    }
    
    loadingEl.innerHTML = `
        <div style="
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            min-width: 300px;
        ">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0" style="font-size: 16px; font-weight: 500; color: #333;">
                ${message}
            </p>
            <small class="text-muted mt-2 d-block" style="font-size: 12px;">
                Harap tunggu...
            </small>
        </div>
    `;
    
    loadingEl.style.display = 'flex';
}

function hideLoading() {
    const loadingEl = document.getElementById('loadingOverlay');
    if (loadingEl) {
        loadingEl.style.display = 'none';
    }
}

function showErrorModal(title, message) {
    console.error(`❌ ${title}:`, message);
    
    document.getElementById('errorTitle').textContent = title;
    document.getElementById('errorMessage').textContent = message;
    
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
}

// ========= BUYER SEARCH FUNCTIONS =========

function searchBuyers(keyword) {
    const dropdown = document.getElementById('buyerDropdown');
    
    fetch(`/cashier/buyers/search?keyword=${encodeURIComponent(keyword)}`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.length === 0) {
                dropdown.innerHTML = '<div class="autocomplete-item text-muted">Tidak ada hasil</div>';
                dropdown.style.display = 'block';
                return;
            }
            
            dropdown.innerHTML = '';
            data.forEach(buyer => {
                dropdown.innerHTML += `
                    <div class="autocomplete-item" onclick="selectBuyer(${buyer.id}, '${buyer.name.replace(/'/g, "\\'")}', '${buyer.phone || '-'}')">
                        <span class="buyer-name">${buyer.name}</span>
                        <span class="buyer-phone">${buyer.phone || 'No phone'}</span>
                    </div>
                `;
            });
            dropdown.style.display = 'block';
        })
        .catch(error => {
            console.error('Error searching buyers:', error);
        });
}

function selectBuyer(id, name, phone) {
    selectedBuyer = { id, name, phone };
    
    document.getElementById('buyerId').value = id;
    document.getElementById('buyerSearch').value = name;
    document.getElementById('buyerDropdown').style.display = 'none';
    
    const selectedDiv = document.getElementById('selectedBuyer');
    selectedDiv.innerHTML = `
        <div class="buyer-info">
            <div class="buyer-name">${name}</div>
            <div class="buyer-phone">${phone}</div>
        </div>
        <button class="remove-buyer" onclick="clearBuyer()">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    selectedDiv.style.display = 'flex';
    updatePayButtonState();
}

function clearBuyer() {
    selectedBuyer = null;
    document.getElementById('buyerId').value = '';
    document.getElementById('buyerSearch').value = '';
    document.getElementById('selectedBuyer').style.display = 'none';
    updatePayButtonState();
}
</script>