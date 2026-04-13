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
        const description = card.data('description');
        const type = card.data('type');
        const typeText = type === 'public' ? 'Public' : 'Private';
        const typeIcon = type === 'public' ? 'bi-globe' : 'bi-shield-lock';
        const typeColor = type === 'public' ? '#22c55e' : '#f59e0b';
        const createdAt = card.find('.komunitas-meta:last span').text();
        const admin = card.find('.komunitas-meta:first span').text();
        const logo = card.find('.komunitas-avatar').attr('src');

        Swal.fire({
            title: `
                <div class="modal-header-custom">
                    ${logo 
                        ? `<img src="${logo}" class="modal-avatar">`
                        : `<div class="modal-avatar placeholder">
                                <i class="bi bi-people-fill"></i>
                        </div>`
                    }
                    <div class="modal-title-row">
                        <h4 class="modal-title">${title}</h4>
                        <span class="modal-badge ${type}">
                            <i class="bi ${typeIcon}"></i> ${typeText}
                        </span>
                    </div>
                </div>
            `,
            html: `
                <div class="modal-body-custom">
                    <h6>Deskripsi:</h6>
                    <p class="komunitas-description">{{ $item->description }}</p><br>

                    <div class="modal-grid">
                        <div class="modal-box">
                            <i class="bi bi-person-circle"></i>
                            <div>
                                <small>Admin</small>
                                <strong>${admin}</strong>
                            </div>
                        </div>

                        <div class="modal-box">
                            <i class="bi bi-calendar-event"></i>
                            <div>
                                <small>Dibuat</small>
                                <strong>${createdAt}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: 480,
            padding: 0,
            background: '#ffffff',
            customClass: {
                popup: 'modal-clean'
            }
        });
    });
});

$(document).on('click', '.btn-ban', function () {

    if ($(this).is(':disabled')) return;

    let id = $(this).data('id');

    Swal.fire({
        title: 'Yakin mau ban komunitas ini?',
        text: "Komunitas yang dibanned tidak bisa diakses lagi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {

        fetch(`/admin/community/${id}/ban`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
            .then(res => {
                if (!res.ok) throw new Error('Request gagal');
                return res.json();
            })
            .then(data => {
                Swal.fire(
                    'Berhasil!',
                    'Komunitas sudah dibanned.',
                    'success'
                ).then(() => {
                    location.reload();
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire(
                    'Error!',
                    'Terjadi kesalahan.',
                    'error'
                );
            });

        }
    });
});
</script>