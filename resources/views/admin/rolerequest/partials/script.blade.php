<script>
$(document).ready(function() {
    // Setup CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Filter requests function
    window.filterRequests = function(status) {
        const rows = document.querySelectorAll('.request-row');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Reset semua button
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Set button aktif
        const activeButton = document.querySelector(`button[onclick="filterRequests('${status}')"]`);
        if (activeButton) {
            activeButton.classList.add('active');
        }
        
        // Filter rows
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else if (row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Handle Approve Button
    $(document).on('click', '.approve-btn', function() {
        const requestId = $(this).data('id');
        const userName = $(this).data('user');
        const currentRole = $(this).data('current-role');
        const userPhone = $(this).data('phone');
        const avatarText = userName.charAt(0).toUpperCase();
        
        // Populate modal data
        $('#approveRequestId').val(requestId);
        $('#approveUserName').text(userName);
        $('#approveUserPhone').text(userPhone);
        $('#approveAvatar').text(avatarText);
        
        // Set current role badge
        const currentRoleBadge = $('#approveCurrentRole');
        currentRoleBadge.text(currentRole.toUpperCase());
        currentRoleBadge.removeClass().addClass('role-badge-cell');
        
        if (currentRole === 'penyewa') {
            currentRoleBadge.addClass('penyewa');
        } else if (currentRole === 'pemilik') {
            currentRoleBadge.addClass('pemilik');
        } else if (currentRole === 'admin') {
            currentRoleBadge.addClass('admin');
        }
        
        $('#approveModal').modal('show');
    });

    // Handle Reject Button
    $(document).on('click', '.reject-btn', function() {
        const requestId = $(this).data('id');
        const userName = $(this).data('user');
        const currentRole = $(this).data('current-role');
        const userPhone = $(this).data('phone');
        const avatarText = userName.charAt(0).toUpperCase();
        
        // Populate modal data
        $('#rejectRequestId').val(requestId);
        $('#rejectUserName').text(userName);
        $('#rejectUserPhone').text(userPhone);
        $('#rejectAvatar').text(avatarText);
        $('#rejectCurrentRoleText').text(currentRole);
        
        // Set current role badge
        const currentRoleBadge = $('#rejectCurrentRole');
        currentRoleBadge.text(currentRole.toUpperCase());
        currentRoleBadge.removeClass().addClass('role-badge-cell');
        
        if (currentRole === 'penyewa') {
            currentRoleBadge.addClass('penyewa');
        } else if (currentRole === 'pemilik') {
            currentRoleBadge.addClass('pemilik');
        } else if (currentRole === 'admin') {
            currentRoleBadge.addClass('admin');
        }
        
        $('#rejectModal').modal('show');
    });

    // Handle Approve Form Submit
    $('#approveForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        $.ajax({
            url: '{{ route("admin.rolerequest.approve") }}',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
            },
            success: function(response) {
                if (response.success) {
                    $('#approveModal').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonColor: '#22c55e',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message,
                        confirmButtonColor: '#22c55e'
                    });
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyetujui permintaan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#22c55e'
                });
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Handle Reject Form Submit
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        $.ajax({
            url: '{{ route("admin.rolerequest.reject") }}',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
            },
            success: function(response) {
                if (response.success) {
                    $('#rejectModal').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonColor: '#22c55e',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message,
                        confirmButtonColor: '#22c55e'
                    });
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menolak permintaan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#22c55e'
                });
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Reset form ketika modal ditutup
    $('#approveModal, #rejectModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('button[type="submit"]').prop('disabled', false);
    });
});
</script>