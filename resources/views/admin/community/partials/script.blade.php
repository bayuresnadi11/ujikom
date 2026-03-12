<script>
$(document).ready(function() {
    function filterKomunitas() {
        const searchValue = $('#searchInput').val().toLowerCase();
        const typeValue = $('#typeFilter').val();
        let visibleCount = 0;
        let publicCount = 0;
        let privateCount = 0;

        $('.komunitas-card').each(function() {
            const name = $(this).data('name');
            const description = $(this).data('description');
            const type = $(this).data('type');
            
            const matchesSearch = !searchValue || 
                name.includes(searchValue) || 
                description.includes(searchValue);
            const matchesType = !typeValue || type === typeValue;
            
            if (matchesSearch && matchesType) {
                $(this).show();
                visibleCount++;
                if (type === 'public') publicCount++;
                if (type === 'private') privateCount++;
            } else {
                $(this).hide();
            }
        });

        $('#totalKomunitas').text(visibleCount);
        $('#statsTotal').text(visibleCount);
        $('#statsPublic').text(publicCount);
        $('#statsPrivate').text(privateCount);

        if (visibleCount === 0 && searchValue) {
            if ($('#emptySearchState').length === 0) {
                $('#komunitasGrid').append(`
                    <div id="emptySearchState" class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4 class="empty-title">Tidak ditemukan</h4>
                        <p class="empty-description">
                            Tidak ada komunitas yang sesuai dengan pencarian "<strong>${searchValue}</strong>"
                        </p>
                    </div>
                `);
            }
        } else {
            $('#emptySearchState').remove();
        }
    }

    $('#searchInput').on('keyup', filterKomunitas);
    $('#typeFilter').on('change', filterKomunitas);

    $('#resetFilter').on('click', function() {
        $('#searchInput').val('');
        $('#typeFilter').val('');
        filterKomunitas();
    });

    $(document).on('click', '.view-detail', function() {
        const id = $(this).data('id');
        const card = $(this).closest('.komunitas-card');
        const title = card.find('.komunitas-title').text();
        const description = card.find('.komunitas-description').text();
        const type = card.data('type');
        const typeText = type === 'public' ? 'Public' : 'Private';
        const typeIcon = type === 'public' ? 'bi-globe' : 'bi-shield-lock';
        const typeColor = type === 'public' ? '#22c55e' : '#f59e0b';
        const createdAt = card.find('.komunitas-meta:last span').text();
        const admin = card.find('.komunitas-meta:first span').text();
        const logo = card.find('.komunitas-avatar').attr('src');

        Swal.fire({
            title: `<div style="text-align: left;">
                       <div style="display: flex; align-items: center; margin-bottom: 20px;">
                           ${logo ? `<img src="${logo}" style="width: 64px; height: 64px; border-radius: 14px; object-fit: cover; margin-right: 16px; border: 3px solid white; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);">` : 
                             `<div style="width: 64px; height: 64px; border-radius: 14px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); display: flex; align-items: center; justify-content: center; margin-right: 16px; border: 3px solid white; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);">
                                 <i class="bi bi-people-fill" style="font-size: 28px; color: white;"></i>
                             </div>`}
                           <div>
                               <h4 style="margin: 0 0 8px 0; color: #1a5c37;">${title}</h4>
                               <span style="background: ${typeColor}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                   <i class="bi ${typeIcon}"></i> ${typeText}
                               </span>
                           </div>
                       </div>
                    </div>`,
            html: `
                <div style="text-align: left;">
                    <h6 style="color: #1a5c37; font-weight: 600; margin-bottom: 12px;">Deskripsi:</h6>
                    <p style="color: #6b7280; line-height: 1.6; background: #f0fdf4; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
                        ${description}
                    </p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div style="border: 1px solid #e5f5ec; border-radius: 12px; padding: 16px; background: white;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="bi bi-person-circle" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <small style="color: #9ca3af; display: block; margin-bottom: 4px;">Admin</small>
                                    <strong style="color: #1a5c37;">${admin}</strong>
                                </div>
                            </div>
                        </div>
                        <div style="border: 1px solid #e5f5ec; border-radius: 12px; padding: 16px; background: white;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="bi bi-calendar-event" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <small style="color: #9ca3af; display: block; margin-bottom: 4px;">Dibuat</small>
                                    <strong style="color: #1a5c37;">${createdAt}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid #e5f5ec; padding-top: 16px; margin-top: 16px;">
                        <small style="color: #9ca3af;">
                            <i class="bi bi-hash"></i> ID: #${String(id).padStart(4, '0')}
                        </small>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '550px',
            padding: '24px',
            confirmButtonColor: '#22c55e',
            customClass: {
                popup: 'rounded-4 border-0 shadow-lg'
            }
        });
    });
});
</script>