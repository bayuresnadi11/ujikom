<script>
    // Toast notification - Sama dengan menu
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

    function searchVenue() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.venue-card');
        
        cards.forEach(card => {
            const venueName = card.getAttribute('data-venue-name') || '';
            const venueLocation = card.getAttribute('data-venue-location') || '';
            
            if (venueName.includes(input) || venueLocation.includes(input)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Category Filter
    document.addEventListener('DOMContentLoaded', function() {
        const filterChips = document.querySelectorAll('.filter-chip');
        const venueCards = document.querySelectorAll('.venue-card');
        
        filterChips.forEach(chip => {
            chip.addEventListener('click', function() {
                // Remove active class from all chips
                filterChips.forEach(c => c.classList.remove('active'));
                // Add active class to clicked chip
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                
                // Filter venue cards
                venueCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });

    // Add click effects to venue cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.venue-card');
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
    });
</script>