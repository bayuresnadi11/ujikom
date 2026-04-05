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
        }, 3500);
    }

    function showNotification() {
        showToast('Anda memiliki 3 notifikasi baru', 'info');
    }

    // Category Filter
    document.addEventListener('DOMContentLoaded', function() {
        const filterChips = document.querySelectorAll('.filter-chip');
        const tipCards = document.querySelectorAll('.tip-card');
        const tipSections = document.querySelectorAll('.tips-section');
        const emptyState = document.getElementById('empty-state');
        const searchInput = document.getElementById('searchInput');

        // Filter by category
        filterChips.forEach(chip => {
            chip.addEventListener('click', function() {
                filterChips.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                filterTipsByCategory(this.getAttribute('data-category'));
            });
        });

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterTipsBySearch(searchTerm);
        });

        // Initialize scrolling functionality
        initScrollContainers();

        function filterTipsByCategory(category) {
            let visibleTipsCount = 0;
            
            if (category === 'all') {
                tipSections.forEach(section => {
                    section.style.display = 'block';
                });
                tipCards.forEach(card => {
                    card.style.display = 'block';
                });
                emptyState.style.display = 'none';
            } else {
                tipSections.forEach(section => {
                    section.style.display = 'none';
                });
                
                tipCards.forEach(card => {
                    card.style.display = 'none';
                });
                
                tipCards.forEach(card => {
                    if (card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                        visibleTipsCount++;
                        
                        const parentSection = card.closest('.tips-section');
                        if (parentSection) {
                            parentSection.style.display = 'block';
                        }
                    }
                });
                
                if (visibleTipsCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }
        }

        function filterTipsBySearch(searchTerm) {
            if (searchTerm === '') {
                const activeChip = document.querySelector('.filter-chip.active');
                if (activeChip) {
                    filterTipsByCategory(activeChip.getAttribute('data-category'));
                }
                return;
            }

            let visibleTipsCount = 0;
            
            tipSections.forEach(section => {
                section.style.display = 'none';
            });
            
            tipCards.forEach(card => {
                card.style.display = 'none';
            });
            
            tipCards.forEach(card => {
                const title = card.querySelector('.tip-title').textContent.toLowerCase();
                const content = card.querySelector('.tip-content').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleTipsCount++;
                    
                    const parentSection = card.closest('.tips-section');
                    if (parentSection) {
                        parentSection.style.display = 'block';
                    }
                }
            });
            
            if (visibleTipsCount === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        // Add click effects to tip cards
        tipCards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON' || e.target.closest('button')) return;
                
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });

    // Initialize scroll containers
    function initScrollContainers() {
        const scrollContainers = document.querySelectorAll('.tips-scroll-container');
        
        scrollContainers.forEach(container => {
            // Add scroll event listener
            container.addEventListener('wheel', function(e) {
                if (e.deltaY === 0) return;
                e.preventDefault();
                this.scrollLeft += e.deltaY;
            });
            
            // Touch scroll support
            let startX, scrollLeft, isDown = false;
            
            container.addEventListener('touchstart', (e) => {
                isDown = true;
                startX = e.touches[0].pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
            });
            
            container.addEventListener('touchend', () => {
                isDown = false;
            });
            
            container.addEventListener('touchmove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.touches[0].pageX - container.offsetLeft;
                const walk = (x - startX) * 2;
                container.scrollLeft = scrollLeft - walk;
            });
        });
    }

    // Bookmark functionality
    function toggleBookmark(button) {
        const icon = button.querySelector('i');
        
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            button.innerHTML = '<i class="fas fa-bookmark"></i> Tersimpan';
            button.classList.add('bookmarked');
            showToast('Tips disimpan ke bookmark', 'success');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            button.innerHTML = '<i class="far fa-bookmark"></i> Simpan';
            button.classList.remove('bookmarked');
            showToast('Tips dihapus dari bookmark', 'info');
        }
        
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
    }

    // Share functionality
    function shareTip(button) {
        const tipCard = button.closest('.tip-card');
        const tipTitle = tipCard.querySelector('.tip-title').textContent;
        const tipContent = tipCard.querySelector('.tip-content').textContent.substring(0, 100) + '...';
        
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
        
        if (navigator.share) {
            navigator.share({
                title: tipTitle,
                text: tipContent,
                url: window.location.href
            }).then(() => {
                showToast('Tips berhasil dibagikan', 'success');
            }).catch(error => {
                console.log('Error sharing:', error);
                copyToClipboard(tipTitle, tipContent);
            });
        } else {
            copyToClipboard(tipTitle, tipContent);
        }
    }

    function copyToClipboard(tipTitle, tipContent) {
        const textToCopy = `${tipTitle}\n\n${tipContent}\n\nLihat tips lengkapnya di aplikasi SewaLap.`;
        
        navigator.clipboard.writeText(textToCopy).then(() => {
            showToast('Tips berhasil disalin ke clipboard', 'success');
        }).catch(err => {
            const textArea = document.createElement('textarea');
            textArea.value = textToCopy;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('Tips berhasil disalin ke clipboard', 'success');
        });
    }

    // Initialize with some bookmarks
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const firstBookmarkBtn = document.querySelector('.tip-action-btn');
            if (firstBookmarkBtn) {
                toggleBookmark(firstBookmarkBtn);
            }
        }, 1000);
    });
</script>