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
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Tambah Section Baru';
        document.getElementById('sectionForm').reset();
        document.getElementById('section_id').value = '';
        document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-save"></i> Simpan Section';
        document.getElementById('modalForm').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalForm').style.display = 'none';
        document.getElementById('modalHapus').style.display = 'none';
    }

    function editSection(id) {
    fetch(`/landowner/section-lapangan/${id}/edit`)
        .then(response => response.json())
        .then(res => {
            if (!res.success) {
                throw new Error('Response tidak valid');
            }

            const section = res.section; // 🔥 AMBIL DATA YANG BENAR

            document.getElementById('modalTitle').innerHTML =
                '<i class="fas fa-edit"></i> Edit Section';

            document.getElementById('section_id').value = section.id;
            document.getElementById('section_name').value = section.section_name;
            document.getElementById('description').value = section.description ?? '';

            document.getElementById('btnSubmit').innerHTML =
                '<i class="fas fa-save"></i> Update Section';

            document.getElementById('modalForm').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal memuat data section', 'error');
        });
}

    function deleteSection(id, name) {
        document.getElementById('hapusNamaSection').textContent = name;
        document.getElementById('btnConfirmHapus').onclick = function() {
            // Implementasi AJAX untuk menghapus section
            fetch(`/landowner/section-lapangan/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Section berhasil dihapus', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast('Gagal menghapus section', 'error');
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

   document.getElementById('sectionForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const sectionId = document.getElementById('section_id').value;

    let url = '/landowner/section-lapangan';
    let method = 'POST';

    if (sectionId) {
        url = `/landowner/section-lapangan/${sectionId}`;
        formData.append('_method', 'PUT'); // 🔥 METHOD SPOOFING
    }

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(
                sectionId
                    ? 'Section berhasil diperbarui'
                    : 'Section berhasil ditambahkan',
                'success'
            );

            setTimeout(() => location.reload(), 1200);
        } else {
            showToast(data.message || 'Terjadi kesalahan', 'error');
        }

        closeModal();
    })
    .catch(error => {
        console.error(error);
        showToast('Terjadi kesalahan', 'error');
        closeModal();
    });
});


    // Fungsi pencarian
    function searchSection() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.section-card');
        
        cards.forEach(card => {
            const sectionName = card.getAttribute('data-section-name');
            if (sectionName.includes(input)) {
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

    // Add click effects to section cards
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