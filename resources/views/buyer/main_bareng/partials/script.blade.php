<script>
        let currentDeleteId = null;
        let currentDetailId = null;
        let currentBookingId = null;

        // Switch Tabs
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.add('active');
            document.querySelectorAll('.tab-btn').forEach(btn => {
                if (btn.textContent.includes(tabName === 'list' ? 'List' : 'Buat Baru')) {
                    btn.classList.add('active');
                }
            });
        }

        // Select Booking
        function selectBooking(element) {
            // Remove selected class from all items
            document.querySelectorAll('.booking-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selected class to clicked item
            element.classList.add('selected');
            currentBookingId = element.dataset.bookingId;
            document.getElementById('selected_booking_id').value = currentBookingId;
            document.getElementById('createForm').style.display = 'block';
            
            // Scroll to form
            document.getElementById('createForm').scrollIntoView({ behavior: 'smooth' });
            
            // Reset form
            document.getElementById('playTogetherForm').reset();
            document.getElementById('edit_id').value = '';
            clearErrors();
            togglePriceField();
            
            // Load booking details if needed
            loadBookingDetails(currentBookingId);
        }

        // Load Booking Details
        async function loadBookingDetails(bookingId) {
            try {
                const response = await fetch(`{{ route('buyer.main_bareng.show', ':id') }}`.replace(':id', bookingId), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const booking = result.data;
                    console.log('Booking loaded:', booking);
                }
            } catch (error) {
                console.error('Error loading booking details:', error);
            }
        }

        // Toggle Price Field
        function togglePriceField() {
            const type = document.querySelector('input[name="type"]:checked').value;
            const priceField = document.getElementById('price_field');
            const priceInput = document.getElementById('price_per_person');
            
            if (type === 'paid') {
                priceField.style.display = 'block';
                priceInput.required = true;
            } else {
                priceField.style.display = 'none';
                priceInput.required = false;
                priceInput.value = '';
            }
        }

        // Open Edit Modal
        window.openEditModal = async function(id) {
            try {
                showLoading();
                
                const url = "{{ route('buyer.main_bareng.edit', ':id') }}".replace(':id', id);
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                
                // Switch to create tab
                switchTab('create');
                
                // Show form
                document.getElementById('createForm').style.display = 'block';
                document.getElementById('edit_id').value = data.id;
                document.getElementById('selected_booking_id').value = data.booking_id;
                
                // Pre-select booking
                document.querySelectorAll('.booking-item').forEach(item => {
                    if (item.dataset.bookingId == data.booking_id) {
                        item.classList.add('selected');
                    } else {
                        item.classList.remove('selected');
                    }
                });
                
                // Fill form data
                document.getElementById('community_id').value = data.community_id || '';
                document.getElementById('max_participants').value = data.max_participants;
                
                document.querySelectorAll('input[name="type"]').forEach(radio => {
                    radio.checked = radio.value === data.type;
                });
                
                if (data.type === 'paid') {
                    document.getElementById('price_per_person').value = data.price_per_person;
                }
                
                document.getElementById('payment_type').value = data.payment_type;
                document.querySelectorAll('input[name="privacy"]').forEach(radio => {
                    radio.checked = radio.value === data.privacy;
                });
                
                document.getElementById('gender').value = data.gender;
                document.getElementById('host_approval').checked = data.host_approval == 1;
                document.getElementById('description').value = data.description || '';
                
                togglePriceField();
                clearErrors();
                hideLoading();
            } catch (error) {
                hideLoading();
                showToast('Gagal memuat data', 'error');
                console.error('Error loading data:', error);
            }
        };

        // Show Detail
        async function showDetail(id) {
            try {
                showLoading();
                
                const url = "{{ route('buyer.main_bareng.show', ':id') }}".replace(':id', id);
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Failed to load detail');
                }
                
                const data = result.data;
                currentDetailId = data.id;
                
                // Format date
                const date = new Date(data.date);
                const formattedDate = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // Format price
                let priceText = 'FREE';
                if (data.type === 'paid') {
                    priceText = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(data.price_per_person) + '/orang';
                }
                
                // Determine gender icon and text
                let genderIcon, genderText;
                if (data.gender === 'mixed') {
                    genderIcon = 'fa-venus-mars';
                    genderText = 'Campur (Mixed)';
                } else if (data.gender === 'male') {
                    genderIcon = 'fa-mars';
                    genderText = 'Laki-laki saja';
                } else {
                    genderIcon = 'fa-venus';
                    genderText = 'Perempuan saja';
                }
                
                // Determine payment type text
                let paymentText = data.payment_type === 'split' ? 'Dibagi rata' : 'Dibayar host';
                
                // Get venue photo
                let venuePhoto = '{{ asset("images/default-venue.jpg") }}';
                if (data.booking?.venue?.photos?.[0]?.photo_path) {
                    venuePhoto = '{{ Storage::url("") }}' + data.booking.venue.photos[0].photo_path;
                }
                
                // Get booking code
                const bookingCode = data.booking?.ticket_code || 'N/A';
                
                // Get category name
                const categoryName = data.booking?.venue?.category?.category_name || '-';
                
                // Build detail HTML
                const detailHTML = `
                    <div class="detail-header">
                        <div style="width: 120px; height: 120px; margin: 0 auto 16px; border-radius: var(--radius-lg); overflow: hidden;">
                            <img src="${venuePhoto}" alt="Venue Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h2 class="detail-title">${data.booking?.venue?.venue_name || 'Main Bareng'}</h2>
                        <div class="detail-subtitle">
                            <i class="fas fa-map-marker-alt"></i>
                            ${data.location}
                        </div>
                        <div style="margin-top: 8px; font-size: 13px; color: var(--text-light);">
                            <i class="fas fa-ticket-alt"></i> Booking ID: <strong>${bookingCode}</strong>
                        </div>
                    </div>
                    
                    <div class="detail-body">
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Informasi Utama
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        Tanggal
                                    </div>
                                    <div class="detail-value">${formattedDate}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-tag"></i>
                                        Olahraga
                                    </div>
                                    <div class="detail-value">${categoryName}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-users"></i>
                                        Komunitas
                                    </div>
                                    <div class="detail-value">${data.community?.name || 'Tanpa Komunitas'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-flag"></i>
                                        Status
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge badge-${data.status}">
                                            ${data.status.toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-user-friends"></i>
                                Detail Partisipan
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-users"></i>
                                        Maksimal Partisipan
                                    </div>
                                    <div class="detail-value">${data.max_participants} orang</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas ${genderIcon}"></i>
                                        Gender Peserta
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge badge-gender">${genderText}</span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Biaya
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge ${data.type === 'paid' ? 'badge-cost' : 'badge-free'}">
                                            ${priceText}
                                        </span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-credit-card"></i>
                                        Pembayaran
                                    </div>
                                    <div class="detail-value">${paymentText}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-cog"></i>
                                Pengaturan
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-lock"></i>
                                        Privasi
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge badge-${data.privacy}">
                                            <i class="fas fa-${data.privacy == 'public' ? 'globe' : 'lock'}"></i>
                                            ${data.privacy.toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-user-check"></i>
                                        Persetujuan Host
                                    </div>
                                    <div class="detail-value">
                                        ${data.host_approval ? 
                                            '<span class="badge badge-host-yes"><i class="fas fa-user-check"></i> Diperlukan</span>' : 
                                            '<span class="badge badge-host-no"><i class="fas fa-bolt"></i> Auto Join</span>'}
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-user"></i>
                                        Dibuat Oleh
                                    </div>
                                    <div class="detail-value">${data.creator?.name || 'Tidak diketahui'}</div>
                                </div>
                            </div>
                        </div>
                        
                        ${data.description ? `
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-align-left"></i>
                                Deskripsi
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-item" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                                    <div class="detail-value" style="text-align: left; font-weight: normal; line-height: 1.6;">
                                        ${data.description}
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('detailContent').innerHTML = detailHTML;
                document.getElementById('detailModal').classList.add('active');
                
                hideLoading();
            } catch (error) {
                hideLoading();
                showToast('Gagal memuat detail', 'error');
                console.error('Error loading detail:', error);
            }
        }

        // Close Detail Modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
            currentDetailId = null;
        }

        // Edit from Detail
        function editFromDetail() {
            if (currentDetailId) {
                closeDetailModal();
                openEditModal(currentDetailId);
            }
        }

        // Clear Form Errors
        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('error');
            });
        }

        // Show Error Messages
        function showErrors(errors) {
            clearErrors();
            for (const [field, messages] of Object.entries(errors)) {
                const input = document.getElementById(field);
                const errorEl = document.getElementById(field + '_error');
                if (input) {
                    input.classList.add('error');
                }
                if (errorEl && messages && messages.length > 0) {
                    errorEl.textContent = messages[0];
                    errorEl.classList.add('show');
                }
            }
        }

        // Submit Form
        async function submitForm(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const id = formData.get('id');

            // Check if booking is selected
            if (!formData.get('booking_id')) {
                showToast('Pilih booking terlebih dahulu', 'error');
                return;
            }

            // Convert checkbox value
            formData.set('host_approval', document.getElementById('host_approval').checked ? 1 : 0);

            // Convert FormData to object
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            try {
                let url, method;
                if (id) {
                    url = "{{ route('buyer.main_bareng.update', ':id') }}".replace(':id', id);
                    method = 'PUT';
                } else {
                    url = '{{ route('buyer.main_bareng.store') }}';
                    method = 'POST';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast(result.message, 'success');
                    
                    // Clear form
                    form.reset();
                    document.querySelectorAll('.booking-item').forEach(item => {
                        item.classList.remove('selected');
                    });
                    document.getElementById('createForm').style.display = 'none';
                    document.getElementById('edit_id').value = '';
                    
                    // Switch to list tab and reload
                    setTimeout(() => {
                        switchTab('list');
                        window.location.reload();
                    }, 1500);
                } else {
                    if (result.errors) {
                        showErrors(result.errors);
                    } else {
                        showToast(result.message || 'Terjadi kesalahan', 'error');
                    }
                }
            } catch (error) {
                showToast('Terjadi kesalahan jaringan', 'error');
                console.error('Error submitting form:', error);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan';
            }
        }

        // Confirm Delete
        function confirmDelete(id, name) {
            currentDeleteId = id;
            document.getElementById('confirmTitle').textContent = `Hapus ${name}`;
            document.getElementById('confirmMessage').textContent =
                `Apakah Anda yakin ingin menghapus "${name}"? Tindakan ini tidak dapat dibatalkan.`;
            document.getElementById('confirmModal').classList.add('active');

            // Set up delete button handler
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.onclick = () => deleteItem(id);
        }

        // Close Confirm Modal
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('active');
            currentDeleteId = null;
        }

        // Delete Item
        async function deleteItem(id) {
            try {
                const deleteBtn = document.getElementById('confirmDeleteBtn');
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';

                const response = await fetch("{{ route('buyer.main_bareng.destroy', ':id') }}".replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast(result.message, 'success');
                    closeConfirmModal();

                    // Remove card from container
                    const card = document.querySelector(`.card[data-id="${id}"]`);
                    if (card) {
                        card.remove();

                        // Check if container is empty
                        const cardContainer = document.getElementById('cardContainer');
                        if (cardContainer && cardContainer.children.length === 0) {
                            // Reload to show empty state
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                    }
                } else {
                    showToast(result.message || 'Gagal menghapus data', 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan jaringan', 'error');
                console.error('Error deleting item:', error);
            } finally {
                const deleteBtn = document.getElementById('confirmDeleteBtn');
                deleteBtn.disabled = false;
                deleteBtn.textContent = 'Hapus';
            }
        }

        // Search Function
        function searchData() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const cards = document.querySelectorAll('.card');

            let hasVisibleCards = false;
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const isVisible = text.includes(filter);
                card.style.display = isVisible ? '' : 'none';
                if (isVisible) hasVisibleCards = true;
            });

            // Show/hide no data message
            const noData = document.querySelector('.no-data');
            if (noData) {
                noData.style.display = hasVisibleCards ? 'none' : 'block';
            }
        }

        // Show Toast Notification
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle'}"></i>
                </div>
                <div class="toast-content">
                    <h4>${type === 'success' ? 'Sukses!' : type === 'error' ? 'Error!' : 'Info'}</h4>
                    <p>${message}</p>
                </div>
            `;

            container.appendChild(toast);

            // Show toast
            setTimeout(() => toast.classList.add('show'), 10);

            // Remove toast after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 5000);
        }

        // Show Loading
        function showLoading() {
            const loadingEl = document.createElement('div');
            loadingEl.className = 'loading';
            loadingEl.innerHTML = '<i class="fas fa-spinner"></i><p>Memuat...</p>';
            document.getElementById('detailContent').innerHTML = '';
            document.getElementById('detailContent').appendChild(loadingEl);
        }

        // Hide Loading
        function hideLoading() {
            const loadingEl = document.querySelector('.loading');
            if (loadingEl && loadingEl.parentNode) {
                loadingEl.parentNode.removeChild(loadingEl);
            }
        }

        // Close modals on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeConfirmModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('detailModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('detailModal')) {
                closeDetailModal();
            }
        });

        document.getElementById('confirmModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('confirmModal')) {
                closeConfirmModal();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-select first booking if only one available
            const bookingItems = document.querySelectorAll('.booking-item');
            if (bookingItems.length === 1) {
                bookingItems[0].click();
            }
        });
    </script>