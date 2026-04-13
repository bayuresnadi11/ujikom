@extends('layouts.admin')

@push('styles')
    @include('admin.category.partials.style')
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-th-large"></i>
            Daftar Kategori
        </h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Logo</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="kategoriTable">
                    @foreach($kategoris as $kategori)
                    <tr id="row-{{ $kategori->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($kategori->logo)
                                <img src="{{ asset('storage/' . $kategori->logo) }}" alt="Logo" class="logo-preview">
                            @else
                                <span class="text-muted">Tidak ada logo</span>
                            @endif
                        </td>
                        <td><strong>{{ $kategori->category_name }}</strong></td>
                        <td>{{ Str::limit($kategori->description, 50) }}</td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $kategori->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $kategori->id }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori *</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        <small class="text-muted">Format: jpeg, png, jpg, gif, svg (max: 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_kategori" class="form-label">Nama Kategori *</label>
                        <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_logo" class="form-label">Logo</label>
                        <div id="currentLogo" class="mb-2"></div>
                        <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah logo</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.category.partials.script')
@endpush