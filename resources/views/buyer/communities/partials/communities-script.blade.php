<script>
document.addEventListener('DOMContentLoaded', () => {

    // ================= FAB BUTTON FUNCTIONALITY =================
    // Removed FAB functionality as per redesign

    // ================= TAB SWITCHING =================
    // Removed as we are now using a unified list

    // ================= COMMUNITY MODAL =================
    const communityModal = document.getElementById('communityModal');

    function openCommunityModal() {
        // Close FAB if open
        if (typeof fabMenu !== 'undefined' && fabMenu && fabMenu.classList.contains('show')) {
            fabButton.classList.remove('active');
            fabMenu.classList.remove('show');
            document.removeEventListener('click', closeFabOnOutsideClick);
        }
        
        // Open community modal
        communityModal?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCommunityModal() {
        communityModal?.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Event listeners for community modal
    document.querySelector('.btn-add')?.addEventListener('click', openCommunityModal);
    
    communityModal?.addEventListener('click', (e) => {
        if (e.target === communityModal) {
            closeCommunityModal();
        }
    });

    communityModal?.querySelector('.modal-close')?.addEventListener('click', closeCommunityModal);

    // ================= INVITE POPUP =================
    const invitePopup = document.getElementById('invitePopup');

    function openInvitePopup() {
        // Close other modals if open
        closeCommunityModal();
        if (typeof fabMenu !== 'undefined' && fabMenu && fabMenu.classList.contains('show')) {
            fabButton.classList.remove('active');
            fabMenu.classList.remove('show');
            document.removeEventListener('click', closeFabOnOutsideClick);
        }
        
        // Open invite popup
        if (invitePopup) {
            invitePopup.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeInvitePopup() {
        if (invitePopup) {
            invitePopup.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Event listeners for invite popup
    invitePopup?.addEventListener('click', (e) => {
        if (e.target === invitePopup) {
            closeInvitePopup();
        }
    });

    // ================= LIVE SEARCH =================
    const communityList = document.getElementById('communityList');
    const searchInput = document.getElementById('searchInput'); // Fix: Define searchInput
    let searchTimer = null;

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => fetchSearch(searchInput.value), 300);
        });

        async function fetchSearch(keyword) {
            try {
                const url = searchInput.dataset.url + '?q=' + encodeURIComponent(keyword);
                const res = await fetch(url);
                const data = await res.json();

                renderList(communityList, data.communities);
            } catch (err) {
                console.error('Search error:', err);
            }
        }

        function renderList(container, list) {
            if (!container) return;

            if (!list || list.length === 0) {
                container.innerHTML = `<div class="empty-state" style="grid-column: span 2;">
                    <i class="fas fa-search empty-state-icon"></i>
                    <h3 class="empty-state-title">Tidak ditemukan</h3>
                    <p class="empty-state-desc">Tidak ada komunitas yang sesuai dengan pencarian</p>
                </div>`;
                return;
            }

            let html = list.map(c => `
                <a href="/buyer/communities/${c.id}" class="community-item">
                    <div class="community-logo-wrapper">
                        <div class="community-logo-container">
                            <img src="${c.logo && c.logo != 'default.png' ? '/storage/' + c.logo : '/images/default-community.png'}" 
                                 alt="${c.name}" class="community-logo-img">
                        </div>
                        ${c.is_manager ? `
                            <div class="community-badge-admin">ADMIN</div>
                            ${c.pending_count > 0 ? `
                                <div class="community-badge-pending">
                                    ${c.pending_count}
                                </div>
                            ` : ''}
                        ` : ''}
                    </div>
                    <div class="community-info">
                        <div class="community-name">${c.name}</div>
                        <div class="community-category">${c.category?.category_name || '-'}</div>
                    </div>
                </a>
            `).join('');

            container.innerHTML = html;
        }
    }

    // ================= FILTER KATEGORI =================
    const sportSelect = document.getElementById('sport_id');
    if (sportSelect) {
        sportSelect.addEventListener('change', function() {
            const sportId = this.value;
            const categorySelect = document.getElementById('category_id');

            // Reset dropdown kategori
            categorySelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';

            if (sportId) {
                fetch(`/categories-by-sport/${sportId}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.text = category.category_name;
                            categorySelect.appendChild(option);
                        });
                    })
                    .catch(err => console.error('Filter error:', err));
            }
        });
    }

    // ================= KEYBOARD SUPPORT =================
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (communityModal.classList.contains('active')) {
                closeCommunityModal();
            }
            if (invitePopup.style.display === 'flex') {
                closeInvitePopup();
            }
            if (fabMenu.classList.contains('show')) {
                fabButton.classList.remove('active');
                fabMenu.classList.remove('show');
                document.removeEventListener('click', closeFabOnOutsideClick);
            }
        }
    });

    // ================= CHECK FOR INVITES =================
    @if($invitedCommunities && count($invitedCommunities) > 0)
        // Show notification badge
        const notificationBadge = document.querySelector('.notification-badge');
        if (notificationBadge) {
            notificationBadge.textContent = '{{ count($invitedCommunities) }}';
            notificationBadge.style.display = 'flex';
            
            // Auto-show invite popup after 1 second
            setTimeout(() => {
                openInvitePopup();
            }, 1000);
        }
    @endif

});
</script>