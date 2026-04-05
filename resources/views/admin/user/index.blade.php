@extends('layouts.admin')

@push('styles')
    @include('admin.user.partials.style')
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header-box">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                Daftar User
            </h1>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterUsers('all')">
                    Semua
                </button>
                <button class="filter-btn" onclick="filterUsers('admin')">
                    Admin
                </button>
                <button class="filter-btn" onclick="filterUsers('landowner')">
                    LandOwner
                </button>
                <button class="filter-btn" onclick="filterUsers('buyer')">
                    Buyer
                </button>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <h3 class="table-card-title">
                <i class="fas fa-table"></i>
                Data User Sistem
            </h3>
            <span class="count-badge">{{ $users->count() }} User</span>
        </div>
        <div class="table-responsive">
            <table class="table" id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Role</th>
                   
                        <th>Dibuat Pada</th>
                        <th>Diperbarui</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($users as $user)
                        <tr class="user-row" data-role="{{ $user->role }}">
                            <td><strong>{{ $user->id }}</strong></td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->remember_token)
                                    <span class="token-badge">
                                        <i class="fas fa-key"></i> Token
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="tel:{{ $user->phone }}" class="phone-link">
                                    <i class="fas fa-phone"></i>
                                    {{ $user->phone }}
                                </a>
                            </td>
                            <td>
                                <span class="role-badge {{ $user->role }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                           
                            <td>
                                <span class="time-info" title="{{ $user->created_at->format('d F Y H:i:s') }}">
                                    {{ $user->created_at->diffForHumans() }}
                                </span>
                                <span class="time-date">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span class="time-info" title="{{ $user->updated_at->format('d F Y H:i:s') }}">
                                    {{ $user->updated_at->diffForHumans() }}
                                </span>
                                <span class="time-date">{{ $user->updated_at->format('d M Y') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state-row">
                                <div class="empty-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <p class="empty-text">Tidak ada data user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.user.partials.script')
@endpush
