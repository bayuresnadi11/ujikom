<script>
    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        // Set icon based on type
        let icon = 'fa-check-circle';
        if (type === 'error') icon = 'fa-exclamation-circle';
        if (type === 'info') icon = 'fa-info-circle';
        if (type === 'warning') icon = 'fa-exclamation-triangle';
        
        toast.innerHTML = `<i class="fas ${icon}"></i><span id="toast-message">${message}</span>`;
        toast.className = `toast ${type}`;
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }
    
    // Search functionality
    function handleSearch() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput.value.trim()) {
            showToast(`Mencari: "${searchInput.value}"`, 'info');
            // In real app, this would trigger search API
            searchInput.value = '';
        }
    }
    
    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };
    
    // Form submission handlers
    document.getElementById('venueForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('Venue berhasil ditambahkan!', 'success');
        closeModal('addVenueModal');
        this.reset();
    });
    
    // Action functions
    function showNotification() {
        showToast('Anda memiliki 3 notifikasi baru', 'info');
    }
    
    // Make venues slider draggable
    function makeSliderDraggable() {
        const slider = document.getElementById('venuesSlider');
        if (!slider) return;
        
        let isDown = false;
        let startX;
        let scrollLeft;
        
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
        
        // Touch events for mobile
        slider.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        
        slider.addEventListener('touchend', () => {
            isDown = false;
        });
        
        slider.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    }
    
    // Bottom nav active state
    function setActiveNav() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
    
    // Add hover effects to cards
    function addHoverEffects() {
        const cards = document.querySelectorAll('.stat-card, .venue-card, .booking-item');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = this.classList.contains('venue-card') 
                    ? 'translateY(-8px) scale(1.02)' 
                    : 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    }
    
    // Update time-based greeting
    function updateGreeting() {
        const hour = new Date().getHours();
        let greeting = "Selamat Datang";
        
        if (hour < 12) greeting = "Selamat Pagi";
        else if (hour < 15) greeting = "Selamat Siang";
        else if (hour < 19) greeting = "Selamat Sore";
        else greeting = "Selamat Malam";
        
        const welcomeTitle = document.querySelector('.welcome-title');
        if (welcomeTitle) {
            const userName = welcomeTitle.textContent.split(', ')[1] || 'Pemilik!';
            welcomeTitle.textContent = `${greeting}, ${userName}`;
        }
    }
    
    // Initialize venue photo carousels
    function initVenueCarousels() {
        const carousels = document.querySelectorAll('.venue-img-carousel');
        
        carousels.forEach(carousel => {
            const images = carousel.querySelectorAll('.venue-carousel-img');
            const indicators = carousel.querySelectorAll('.indicator');
            
            if (images.length <= 1) return; // No carousel needed for single image
            
            let currentIndex = 0;
            let intervalId;
            
            function showImage(index) {
                images.forEach((img, i) => {
                    img.classList.toggle('active', i === index);
                });
                indicators.forEach((ind, i) => {
                    ind.classList.toggle('active', i === index);
                });
                currentIndex = index;
            }
            
            function nextImage() {
                const nextIndex = (currentIndex + 1) % images.length;
                showImage(nextIndex);
            }
            
            function startAutoSlide() {
                intervalId = setInterval(nextImage, 3000);
            }
            
            function stopAutoSlide() {
                clearInterval(intervalId);
            }
            
            // Click on indicators to change image
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent venue card click
                    showImage(index);
                    stopAutoSlide();
                    startAutoSlide(); // Restart timer
                });
            });
            
            // Pause on hover
            carousel.addEventListener('mouseenter', stopAutoSlide);
            carousel.addEventListener('mouseleave', startAutoSlide);
            
            // Start auto-slide
            startAutoSlide();
        });
    }
    
    // Auto scroll venues slider
    function autoScrollVenues() {
        const slider = document.getElementById('venuesSlider');
        if (!slider || slider.children.length <= 3) return;
        
        let scrollAmount = 0;
        const slideTimer = setInterval(function() {
            slider.scrollLeft += 1;
            scrollAmount += 1;
            
            if (scrollAmount >= 296) { // 280px + 16px gap
                slider.scrollLeft = 0;
                scrollAmount = 0;
            }
        }, 30);
        
        // Pause on hover
        slider.addEventListener('mouseenter', () => clearInterval(slideTimer));
        slider.addEventListener('mouseleave', () => {
            autoScrollVenues(); // Restart animation
        });
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Update greeting based on time
        updateGreeting();
        
        // Set active nav
        setActiveNav();
        
        // Add hover effects
        addHoverEffects();
        
        // Make venues slider draggable if exists
        makeSliderDraggable();
        
        // Initialize venue photo carousels
        initVenueCarousels();
        
        // Auto scroll venues if exists
        autoScrollVenues();
        
        // Search on enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
        
        // Add pulse animation to notification badge
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            setInterval(() => {
                badge.style.animation = 'none';
                setTimeout(() => {
                    badge.style.animation = 'pulse 2s infinite';
                }, 10);
            }, 4000);
        }
        
        // Update greeting every minute
        setInterval(updateGreeting, 60000);
        
        // Venue click tracking
        const venueCards = document.querySelectorAll('.venue-card');
        venueCards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (this.querySelector('.venue-name').textContent === 'Tambah Venue Baru') {
                    showToast('Membuka form tambah venue baru', 'info');
                }
            });
        });
        
        // Booking item click tracking
        const bookingItems = document.querySelectorAll('.booking-item');
        bookingItems.forEach(item => {
            item.addEventListener('click', function() {
                const title = this.querySelector('.booking-title').textContent;
                showToast(`Melihat detail booking: ${title.split(' - ')[0]}`, 'info');
            });
        });
    });
</script>