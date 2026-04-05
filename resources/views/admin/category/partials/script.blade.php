<script>
$(document).ready(function() {
    // Handle Create Form
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.category.store") }}', // PERBAIKAN: admin.category.store
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
            url: '{{ url("admin/category") }}/' + id, // PERBAIKAN: admin/category
            type: 'GET',
            success: function(response) {
                $('#edit_id').val(response.id);
                $('#edit_nama_kategori').val(response.nama_kategori);
                $('#edit_deskripsi').val(response.deskripsi);
                
                var logoHtml = '';
                if(response.logo) {
                    logoHtml = '<p>Logo saat ini:</p>' +
                              '<img src="{{ asset("storage/") }}/' + response.logo + '" alt="Logo" class="logo-preview">';
                } else {
                    logoHtml = '<p class="text-muted">Tidak ada logo</p>';
                }
                $('#currentLogo').html(logoHtml);
                
                $('#editModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengambil data kategori',
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
            url: '{{ url("admin/category") }}/' + id, // PERBAIKAN: admin/category
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
        var nama = $(this).closest('tr').find('td:nth-child(3)').text();
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menghapus kategori '" + nama + "'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/category") }}/' + id, // PERBAIKAN: admin/category
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: response.message,
                            confirmButtonColor: '#22c55e',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        $('#row-' + id).remove();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus kategori',
                            confirmButtonColor: '#22c55e'
                        });
                    }
                });
            }
        });
    });
});
</script>