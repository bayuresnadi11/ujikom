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

    function showFeatureInfo(featureName) {
        showToast(`Fitur ${featureName} akan segera hadir`, 'info');
    }

    // Search functionality
    function searchMenu() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const cards = document.querySelectorAll('.menu-card');
        
        cards.forEach(card => {
            const title = card.querySelector('.menu-title').textContent.toLowerCase();
            const desc = card.querySelector('.menu-desc').textContent.toLowerCase();
            
            if (title.includes(filter) || desc.includes(filter)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Logout confirmation
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin keluar?')) {
            // Create form for logout POST request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Switch to buyer mode
    function switchToBuyer() {
        if (confirm('Beralih ke mode penyewa?')) {
            // Create form for switching role
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("switch.to.buyer") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Welcome message
        setTimeout(() => {
            showToast('Selamat Datang di Menu Landowner!', 'success');
        }, 1000);
        
        // Add click effects to menu cards
        const cards = document.querySelectorAll('.menu-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON') return;
                
                // Add click effect
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });
</script>