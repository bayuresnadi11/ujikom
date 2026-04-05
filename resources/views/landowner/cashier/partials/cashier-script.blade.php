<script>
    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        let icon = 'fa-check-circle';
        
        if (type === 'error') icon = 'fa-exclamation-circle';
        if (type === 'info') icon = 'fa-info-circle';
        if (type === 'warning') icon = 'fa-exclamation-triangle';
        
        toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span>`;
        toast.className = `toast ${type} show`;
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function showNotification() {
        showToast('Anda memiliki 3 notifikasi baru', 'info');
    }

    // Modal functions
    function openModalTambah() {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Tambah Kasir Baru';
        document.getElementById('cashierForm').reset();
        document.getElementById('cashier_id').value = '';
        document.getElementById('password').required = true;
        document.getElementById('passwordLabel').innerHTML = 'Password *';
        document.getElementById('passwordHelp').style.display = 'block';
        document.getElementById('confirmPasswordGroup').style.display = 'none';
        document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-save"></i> Simpan Kasir';
        document.getElementById('modalForm').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalForm').style.display = 'none';
        document.getElementById('modalHapus').style.display = 'none';
    }

    function editCashier(id) {
        fetch(`/landowner/cashier/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Kasir';
                document.getElementById('cashier_id').value = data.id;
                document.getElementById('name').value = data.name;
                document.getElementById('phone').value = data.phone;
                document.getElementById('password').value = '';
                document.getElementById('password').required = false;
                document.getElementById('passwordLabel').innerHTML = 'Password (Kosongkan jika tidak diubah)';
                document.getElementById('passwordHelp').style.display = 'none';
                document.getElementById('confirmPasswordGroup').style.display = 'block';
                document.getElementById('password_confirmation').value = '';
                document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-save"></i> Update Kasir';
                document.getElementById('modalForm').style.display = 'flex';
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal memuat data kasir', 'error');
            });
    }

    function deleteCashier(id, name) {
        document.getElementById('hapusNamaCashier').textContent = name;
        document.getElementById('btnConfirmHapus').onclick = function() {
            fetch(`/landowner/cashier/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Kasir berhasil dihapus', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast('Gagal menghapus kasir', 'error');
                }
                closeModal();
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
                closeModal();
            });
        };
        document.getElementById('modalHapus').style.display = 'flex';
    }

    function showCashierDetail(id) {
        showToast('Fitur detail kasir akan segera hadir', 'info');
    }

    // Form submission dengan AJAX - FIXED
    document.getElementById('cashierForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cashierId = document.getElementById('cashier_id').value;
        const url = cashierId ? `/landowner/cashier/${cashierId}` : '/landowner/cashier';
        const method = cashierId ? 'PUT' : 'POST';
        
        // Untuk PUT request, gunakan JSON format, bukan FormData
        let requestData;
        
        if (cashierId) {
            // Untuk UPDATE: Kirim sebagai JSON dengan semua field yang diperlukan
            requestData = {
                _method: 'PUT',
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            // Tambahkan password hanya jika diisi
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (password) {
                requestData.password = password;
                requestData.password_confirmation = passwordConfirmation;
            }
        } else {
            // Untuk CREATE: Gunakan FormData
            requestData = new FormData(this);
        }
        
        fetch(url, {
            method: cashierId ? 'POST' : 'POST', // Laravel perlu POST dengan _method PUT
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                ...(cashierId ? {'Content-Type': 'application/json'} : {})
            },
            body: cashierId ? JSON.stringify(requestData) : requestData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Terjadi kesalahan');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(cashierId ? 'Kasir berhasil diperbarui' : 'Kasir berhasil ditambahkan', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast(data.message || 'Terjadi kesalahan', 'error');
            }
            closeModal();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Terjadi kesalahan', 'error');
        });
    });

    // Fungsi pencarian
    function searchCashier() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.section-card');
        
        cards.forEach(card => {
            const cashierName = card.getAttribute('data-cashier-name');
            if (cashierName.includes(input)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Event listener untuk modal overlay
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeModal();
        }
    });

    // Add click effects to cashier cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.section-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON' || e.target.closest('button')) return;
                
                // Add click effect
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
        
        // Add animation to mini badges
        const badges = document.querySelectorAll('.mini-badge');
        badges.forEach((badge, index) => {
            badge.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>