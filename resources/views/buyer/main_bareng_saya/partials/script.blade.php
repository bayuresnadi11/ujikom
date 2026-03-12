<script>
    let currentDeleteId = null;

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

            const response = await fetch("{{ route('buyer.main_bareng_saya.destroy', ':id') }}".replace(':id', id), {
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

    // Close modals on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });

    // Close modal when clicking outside
    document.getElementById('confirmModal')?.addEventListener('click', (e) => {
        if (e.target === document.getElementById('confirmModal')) {
            closeConfirmModal();
        }
    });

    // Search with delay
    let searchTimeout;
    document.getElementById('searchInput')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>