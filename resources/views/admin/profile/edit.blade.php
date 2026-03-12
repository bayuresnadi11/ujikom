@extends('layouts.admin')

@push('styles')
    @include('admin.profile.partials.style')
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title mb-4">
                    <i class="bi bi-person-circle me-2"></i>Edit Profile
                </h1>
            </div>
        </div>

        @if(session('sukses'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('gagal'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('gagal') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="profile-avatar mb-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle profile-image mb-3">
                            @else
                                <img src="https://via.placeholder.com/140x140/6c757d/ffffff?text={{ substr($user->name, 0, 1) }}" 
                                     alt="Default Profile" 
                                     class="rounded-circle profile-image mb-3">
                            @endif
                        </div>

                        <div class="mb-2 w-100 text-start">
                            <div class="form-label fw-bold">Foto Profile Sekarang</div>
                            <div class="small text-muted">
                                @if($user->avatar)
                                    {{ basename($user->avatar) }}
                                @else
                                    Tidak ada foto profil
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                                <div class="form-text">Ukuran maksimal 2MB. Format: jpg, png, gif</div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.profile.index') }}" class="btn btn-link me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-lock-fill me-2"></i>Ubah Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update-password') }}" method="POST">
                            @csrf
                            @method('POST')
                            
                            <div class="mb-3">
                                <label class="form-label">Password Sekarang</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required minlength="6">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required minlength="6">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection