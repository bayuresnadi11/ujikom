<script>
/* ===============================
   TOAST
================================ */
function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    const msg = document.getElementById('toast-message');

    const colors = {
        info: 'var(--gradient-primary)',
        warning: 'linear-gradient(135deg,#FF9800,#FF5722)',
        success: 'linear-gradient(135deg,#4CAF50,#2E7D32)'
    };

    toast.style.background = colors[type] || colors.info;
    msg.textContent = message;
    toast.style.display = 'flex';

    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.style.display = 'none', 300);
    }, 3000);
}

/* ===============================
   SEARCH
================================ */
function performSearch() {
    const q = document.getElementById('searchInput').value.trim();
    if (q) showToast(`Mencari: "${q}"`, 'info');
}

/* ===============================
   FILTER CHIP
================================ */
document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', function () {
        document.querySelectorAll('.filter-chip')
            .forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        showToast('Filter diterapkan', 'success');
    });
});

/* ===============================
   SPORT CATEGORY FILTER
================================ */
const emptyFilterEl = document.getElementById('emptyFilterMessage');
const sliderEl = document.getElementById('venuesSlider');

function updateEmptyState() {
    // Jika tidak ada slider (tidak ada data), skip
    if (!sliderEl || !emptyFilterEl) return;

    const allCards = document.querySelectorAll('.slide-card[data-category]');
    let visibleCount = 0;

    allCards.forEach(card => {
        // Hitung yang visible (tidak display:none)
        if (card.style.display !== 'none') {
            visibleCount++;
        }
    });

    // Show/hide empty filter message
    if (visibleCount === 0) {
        sliderEl.style.display = 'none';
        emptyFilterEl.style.display = 'block';
    } else {
        sliderEl.style.display = '';
        emptyFilterEl.style.display = 'none';
    }
}

document.querySelectorAll('.sport-card').forEach(card => {
    card.addEventListener('click', function () {
        // Remove active dari semua
        document.querySelectorAll('.sport-card')
            .forEach(c => c.classList.remove('active'));
        
        // Add active ke yang diklik
        this.classList.add('active');

        const selectedCategory = this.dataset.category;

        // Filter slide cards
        document.querySelectorAll('.slide-card[data-category]').forEach(venue => {
            if (selectedCategory === 'all' || venue.dataset.category === selectedCategory) {
                venue.style.display = '';
            } else {
                venue.style.display = 'none';
            }
        });

        // Update empty state
        updateEmptyState();
    });
});

// Inisialisasi saat load
document.addEventListener('DOMContentLoaded', () => {
    updateEmptyState();
});

/* ===============================
   SLIDER + DOTS (DINAMIS)
================================ */
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('venuesSlider');
    if (!slider) return;

    const dotsBox = document.getElementById('sliderDots');
    if (!dotsBox) return;

    const slides = [...slider.children].filter(el =>
        el.classList.contains('slide-card')
    );

    if (slides.length === 0) return;

    let index = 0;

    function renderDots() {
        dotsBox.innerHTML = '';
        slides.forEach((_, i) => {
            const dot = document.createElement('div');
            dot.className = 'slider-dot' + (i === index ? ' active' : '');
            dot.onclick = () => moveTo(i);
            dotsBox.appendChild(dot);
        });
    }

    function moveTo(i) {
        index = i;
        slider.scrollTo({
            left: slides[i].offsetLeft,
            behavior: 'smooth'
        });
        renderDots();
    }

    slider.addEventListener('scroll', () => {
        let closest = 0, min = Infinity;
        slides.forEach((s, i) => {
            const diff = Math.abs(s.offsetLeft - slider.scrollLeft);
            if (diff < min) {
                min = diff;
                closest = i;
            }
        });
        index = closest;
        renderDots();
    });

    renderDots();
});

/* ===============================
   LIVE SEARCH FUNCTIONALITY
================================ */

let searchTimeout;
const searchInput = document.getElementById('searchInput');
const searchBtn = document.querySelector('.search-btn');

// Elements to hide during search
const sectionsToHide = [
    document.querySelector('.sports-categories'),
    document.querySelector('.recommendations-slider'),
    document.querySelector('.featured-venues')
];

// Create search results container
const searchResultsHTML = `
<div id="searchResults" class="search-results-section" style="display: none;">
    <div class="search-results-header">
        <h2 class="section-title">
            <span class="section-icon"><i class="fas fa-search"></i></span>
            Hasil Pencarian
        </h2>
        <button class="clear-search-btn" onclick="clearSearch()">
            <i class="fas fa-times"></i>
            <span>Tutup</span>
        </button>
    </div>
    
    <div id="searchResultsContent">
        <!-- Results will be loaded here -->
    </div>
</div>
`;

// Insert search results section after search bar
document.querySelector('.search-section').insertAdjacentHTML('afterend', searchResultsHTML);

const searchResultsSection = document.getElementById('searchResults');
const searchResultsContent = document.getElementById('searchResultsContent');

// Search function
async function performSearch() {
    const query = searchInput.value.trim();
    
    if (query.length === 0) {
        clearSearch();
        return;
    }

    // Show loading
    showSearchResults();
    searchResultsContent.innerHTML = `
        <div class="loading-state">
            <div class="spinner"></div>
            <p>Mencari lapangan...</p>
        </div>
    `;

    try {
        // Fetch search results from backend
        const response = await fetch(`/buyer/explore/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();

        if (data.success) {
            displaySearchResults(data.venues, query);
        } else {
            showError('Terjadi kesalahan saat mencari');
        }
    } catch (error) {
        console.error('Search error:', error);
        showError('Gagal melakukan pencarian');
    }
}

// Display search results
function displaySearchResults(venues, query) {
    if (venues.length === 0) {
        searchResultsContent.innerHTML = `
            <div class="empty-wrapper">
                <div class="placeholder-box">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada hasil untuk "${query}"</h3>
                    <p>Coba kata kunci lain atau lihat semua lapangan</p>
                </div>
            </div>
        `;
        return;
    }

    searchResultsContent.innerHTML = `
        <p class="search-count">Ditemukan ${venues.length} lapangan</p>
        <div class="search-results-grid">
            ${venues.map(venue => createVenueCard(venue)).join('')}
        </div>
    `;
}

// Create venue card HTML
function createVenueCard(venue) {
    const rating = venue.rating || 0;
    const fullStars = Math.floor(rating);
    const hasHalfStar = (rating - fullStars) > 0;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    let starsHTML = '';
    for (let i = 0; i < fullStars; i++) {
        starsHTML += '<i class="fas fa-star"></i>';
    }
    if (hasHalfStar) {
        starsHTML += '<i class="fas fa-star-half-alt"></i>';
    }
    for (let i = 0; i < emptyStars; i++) {
        starsHTML += '<i class="far fa-star"></i>';
    }

    return `
        <div class="venue-card">
            <div class="venue-card-image">
                <img src="/storage/${venue.photo}" alt="${venue.venue_name}">
                <div class="category-badge">
                    <i class="fas fa-tag"></i>
                    ${venue.category_name || 'Tanpa Kategori'}
                </div>
                ${venue.rating >= 4.5 ? '<div class="popular-badge"><i class="fas fa-crown"></i> POPULER</div>' : ''}
            </div>
            <div class="venue-card-content">
                <h3 class="venue-name">${venue.venue_name}</h3>
                <div class="venue-rating">
                    <div class="stars">${starsHTML}</div>
                    <span class="rating-text">${rating.toFixed(1)}</span>
                </div>
                <div class="venue-location">
                    <i class="fas fa-location-dot"></i>
                    ${venue.location}
                </div>
                <a href="/buyer/venue/${venue.id}" class="venue-detail-btn">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    `;
}

// Show search results section
function showSearchResults() {
    searchResultsSection.style.display = 'block';
    sectionsToHide.forEach(section => {
        if (section) section.style.display = 'none';
    });
}

// Clear search
function clearSearch() {
    searchInput.value = '';
    searchResultsSection.style.display = 'none';
    sectionsToHide.forEach(section => {
        if (section) section.style.display = '';
    });
    searchResultsContent.innerHTML = '';
}

// Show error
function showError(message) {
    searchResultsContent.innerHTML = `
        <div class="empty-wrapper">
            <div class="placeholder-box">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>${message}</h3>
                <p>Silakan coba lagi</p>
            </div>
        </div>
    `;
}

// Live search on input (debounced)
searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    
    const query = this.value.trim();
    
    if (query.length === 0) {
        clearSearch();
        return;
    }

    // Debounce: wait 500ms after user stops typing
    searchTimeout = setTimeout(() => {
        performSearch();
    }, 500);
});

// Search on button click
searchBtn.addEventListener('click', performSearch);

// Search on Enter key
searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        clearTimeout(searchTimeout);
        performSearch();
    }
});
</script>