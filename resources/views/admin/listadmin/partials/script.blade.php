<script>
$(document).ready(function() {
    // Handle Create Form
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.daftar_admin.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#createModal').modal('hide');
                $('#createForm')[0].reset();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: response.message,
                    confirmButtonColor: '#22c55e',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#22c55e'
                });
            }
        });
    });

    // Handle Edit Button
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '{{ url("admin/daftar_admin") }}/' + id,
            type: 'GET',
            success: function(response) {
                $('#edit_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_phone').val(response.phone);
                
                var avatarHtml = '';
                if(response.avatar) {
                    avatarHtml = '<p>Foto saat ini:</p>' +
                               '<img src="{{ asset("storage/") }}/' + response.avatar + '" alt="Avatar" class="avatar-preview" style="width: 80px; height: 80px;">';
                } else {
                    avatarHtml = '<div class="avatar-preview bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px;">' +
                               '<i class="fas fa-user fa-2x"></i>' +
                               '</div>';
                }
                $('#currentAvatar').html(avatarHtml);
                
                $('#editModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengambil data admin',
                    confirmButtonColor: '#22c55e'
                });
            }
        });
    });

    // Handle Edit Form
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ url("admin/daftar_admin") }}/' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                $('#editModal').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: response.message,
                    confirmButtonColor: '#22c55e',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#22c55e'
                });
            }
        });
    });

    // Handle Delete Button
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        var name = $(this).closest('tr').find('td:nth-child(3)').text().trim();
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menghapus admin '" + name + "'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/daftar_admin") }}/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                confirmButtonColor: '#22c55e',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            $('#row-' + id).remove();
                            
                            // Refresh nomor urut
                            $('#adminTable tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message,
                                confirmButtonColor: '#22c55e'
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonColor: '#22c55e'
                        });
                    }
                });
            }
        });
    });
});
</script>