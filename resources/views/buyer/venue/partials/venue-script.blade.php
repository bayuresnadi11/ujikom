<script>
// CSRF Token Setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ========================================
// SEARCH VENUE
// ========================================
function searchVenue() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.venue-card');
    
    let visibleCount = 0;
    
    cards.forEach(card => {
        const venueName = card.getAttribute('data-venue-name');
        if (venueName.includes(searchTerm)) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state jika tidak ada hasil
    const emptyState = document.querySelector('.empty-state');
    if (visibleCount === 0 && cards.length > 0) {
        if (!emptyState) {
            const container = document.getElementById('venueCards');
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'empty-state search-empty';
            emptyDiv.innerHTML = `
                <i class="fas fa-search empty-state-icon"></i>
                <h3 class="empty-state-title">Venue tidak ditemukan</h3>
                <p class="empty-state-desc">Coba kata kunci pencarian yang berbeda</p>
            `;
            container.appendChild(emptyDiv);
        }
    } else {
        const searchEmpty = document.querySelector('.search-empty');
        if (searchEmpty) {
            searchEmpty.remove();
        }
    }
}

// ========================================
// FILTER BY CATEGORY
// ========================================
function filterByCategory(categoryId) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    // Filter cards
    const cards = document.querySelectorAll('.venue-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const cardCategory = card.getAttribute('data-category-id');
        if (categoryId === 'all' || cardCategory == categoryId) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    const emptyState = document.querySelector('.empty-state');
    if (visibleCount === 0 && cards.length > 0) {
        if (!emptyState) {
            const container = document.getElementById('venueCards');
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'empty-state filter-empty';
            emptyDiv.innerHTML = `
                <i class="fas fa-filter empty-state-icon"></i>
                <h3 class="empty-state-title">Tidak ada venue</h3>
                <p class="empty-state-desc">Tidak ada venue untuk kategori ini</p>
            `;
            container.appendChild(emptyDiv);
        }
    } else {
        const filterEmpty = document.querySelector('.filter-empty');
        if (filterEmpty) {
            filterEmpty.remove();
        }
    }
}

// ========================================
// BOOK VENUE (redirect ke booking dengan venue_id)
// ========================================
function bookVenue(venueId) {
    // Redirect ke halaman booking dengan parameter venue_id
    window.location.href = `/buyer/booking?venue_id=${venueId}`;
}

// ========================================
// TOAST NOTIFICATION
// ========================================
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.querySelector('span').textContent = message;
    toast.className = `toast show ${type}`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function showNotification() {
    showToast('Tidak ada notifikasi baru', 'info');
}

// ========================================
// SMOOTH SCROLL FOR CATEGORY FILTER
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.querySelector('.category-filter');
    
    if (categoryFilter) {
        let isDown = false;
        let startX;
        let scrollLeft;

        categoryFilter.addEventListener('mousedown', (e) => {
            isDown = true;
            categoryFilter.style.cursor = 'grabbing';
            startX = e.pageX - categoryFilter.offsetLeft;
            scrollLeft = categoryFilter.scrollLeft;
        });

        categoryFilter.addEventListener('mouseleave', () => {
            isDown = false;
            categoryFilter.style.cursor = 'grab';
        });

        categoryFilter.addEventListener('mouseup', () => {
            isDown = false;
            categoryFilter.style.cursor = 'grab';
        });

        categoryFilter.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - categoryFilter.offsetLeft;
            const walk = (x - startX) * 2;
            categoryFilter.scrollLeft = scrollLeft - walk;
        });
    }
});

// ========================================
// LAZY LOADING IMAGES (Performance Optimization)
// ========================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('.venue-image img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
</script>