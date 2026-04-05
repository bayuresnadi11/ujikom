@extends('layouts.admin')

@push('styles')
    @include('admin.listadmin.partials.style')
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-users-cog"></i>
            Daftar Admin
        </h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-user-plus"></i> Tambah Admin
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Role</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="adminTable">
                    @foreach($admins as $admin)
                    <tr id="row-{{ $admin->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($admin->avatar)
                                <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar" class="avatar-preview">
                            @else
                                <div class="avatar-preview bg-secondary d-flex align-items-center justify-content-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $admin->name }}</strong></td>
                        <td>{{ $admin->phone }}</td>
                        <td><span class="badge-admin">{{ $admin->role }}</span></td>
                        <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $admin->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $admin->id }}">
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
                <h5 class="modal-title">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon *</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                        <small class="text-muted">Contoh: 081234567890</small>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Foto Profil (Opsional)</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        <small class="text-muted">Format: jpeg, png, jpg, gif (max: 2MB)</small>
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
                <h5 class="modal-title">Edit Data Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Nomor Telepon *</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                    </div>
                    <div class="mb-3">
                        <label for="edit_avatar" class="form-label">Foto Profil</label>
                        <div id="currentAvatar" class="mb-2"></div>
                        <input type="file" class="form-control" id="edit_avatar" name="avatar" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('admin.listadmin.partials.script')
@endpush