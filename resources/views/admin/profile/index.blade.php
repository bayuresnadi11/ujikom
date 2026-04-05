@extends('layouts.admin')

@push('styles')
    @include('admin.profile.partials.style')
@endpush
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title mb-4">
                    <i class="bi bi-person-circle me-2"></i>Profile Admin
                </h1>
            </div>
        </div>

        <!-- Row 1 -->
        <div class="row mb-4">
            <!-- Profile Card - Top Left -->
            <div class="col-lg-3">
                <div class="card profile-card h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <!-- Foto Profil -->
                        <div class="profile-avatar mb-3 d-flex justify-content-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}"
                                    alt="Profile Picture"
                                    class="rounded-circle profile-image">
                            @else
                                <img src="https://via.placeholder.com/120x120/6c757d/ffffff?text={{ substr($user->name, 0, 1) }}"
                                    alt="Default Profile Picture"
                                    class="rounded-circle profile-image">
                            @endif
                        </div>

                        <!-- Nama -->
                        <h4 class="card-title mb-3">{{ $user->name }}</h4>

                        <!-- Telepon -->
                        <div class="profile-info mb-3">
                            <div class="info-item justify-content-center">
                                <i class="bi bi-telephone-fill me-2 text-primary"></i>
                                <span class="info-value">{{ $user->phone }}</span>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="profile-info">
                            <div class="info-item justify-content-center">
                                <i class="bi bi-shield-check-fill me-2 text-success"></i>
                                @if($user->role === 'admin')
                                    <span class="role-badge" style="background: #27AE60;">Admin</span>
                                @elseif($user->role === 'landowner')
                                    <span class="role-badge" style="background: #3498db;">Landowner</span>
                                @elseif($user->role === 'buyer')
                                    <span class="role-badge" style="background: #9b59b6;">Buyer</span>
                                @elseif($user->role === 'cashier')
                                    <span class="role-badge" style="background: #e74c3c;">Cashier</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information Card - Top Right -->
            <div class="col-lg-9">
                <div class="card account-info-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-lines-fill me-2"></i>Informasi Profil
                        </h5>
                        <div class="d-flex gap-2">
                            <!-- PERBAIKAN: Menggunakan route admin.profile.phone -->
                            <a href="{{ route('admin.profile.phone') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-telephone me-1"></i>Edit No.Telepon
                            </a>
                            <!-- PERBAIKAN: Menggunakan route admin.profile.edit -->
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil-square me-1"></i>Edit Profil
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="account-info-grid">
                            <div class="info-item">
                                <div class="stat-content">
                                    <div class="stat-label">
                                        <i class="bi bi-person-fill me-1"></i>Nama
                                    </div>
                                    <div class="stat-value">{{ $user->name }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="stat-content">
                                    <div class="stat-label">
                                        <i class="bi bi-telephone-fill me-1"></i>Telepon
                                    </div>
                                    <div class="stat-value">{{ $user->phone }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="stat-content">
                                    <div class="stat-label">
                                        <i class="bi bi-shield-check-fill me-1"></i>Role
                                    </div>
                                    <div class="stat-value">
                                        @if($user->role === 'admin')
                                            <span class="role-badge" style="background: #27AE60;">Admin</span>
                                        @elseif($user->role === 'landowner')
                                            <span class="role-badge" style="background: #3498db;">Landowner</span>
                                        @elseif($user->role === 'buyer')
                                            <span class="role-badge" style="background: #9b59b6;">Buyer</span>
                                        @elseif($user->role === 'cashier')
                                            <span class="role-badge" style="background: #e74c3c;">Cashier</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="stat-content">
                                    <div class="stat-label">
                                        <i class="bi bi-hash me-1"></i>User ID
                                    </div>
                                    <div class="stat-value">{{ $user->id }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="stat-content">
                                    <div class="stat-label">
                                        <i class="bi bi-calendar-plus-fill me-1"></i>Created At
                                    </div>
                                    <div class="stat-value">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="stat-content">
                                    <div class="stat-label">
                                        <i class="bi bi-pencil-square me-1"></i>Updated At
                                    </div>
                                    <div class="stat-value">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row">
            <!-- Statistics Card - Bottom Left -->
            <div class="col-lg-3">
                <div class="card stats-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up me-2"></i>Statistik
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-plus-fill text-info"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Akun Dibuat</div>
                                    <div class="stat-value">{{ $user->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-pencil-square text-warning"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Terakhir Diubah</div>
                                    <div class="stat-value">{{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-circle-fill text-success"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Status</div>
                                    <div class="stat-value">
                                        <div class="badge bg-success">Aktif</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Card - Bottom Right -->
            <div class="col-lg-9 d-flex">
                <div class="card empty-card flex-fill" style="min-height: 200px;">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Akun Anda terverifikasi</p>
                        <p class="mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i>Nomor telepon: {{ $user->phone }}</p>
                        <p class="mb-0"><i class="bi bi-shield-fill text-warning me-2"></i>Role: {{ ucfirst($user->role) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
