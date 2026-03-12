@extends('layouts.admin')

@push('styles')
    @include('admin.community.partials.style')
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header-section">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h2 class="page-title">
                    <i class="bi bi-people-fill"></i> Daftar Komunitas
                </h2>
                <p class="page-subtitle">Kelola dan pantau semua komunitas yang terdaftar</p>
            </div>
            <div class="total-badge">
                <i class="bi bi-grid"></i>
                <span id="totalKomunitas">{{  $communities->count() }}</span> Komunitas
            </div>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-box-icon total">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-box-value" id="statsTotal">{{  $communities->count() }}</div>
            <div class="stat-box-label">Total Komunitas</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-icon public">
                <i class="bi bi-globe"></i>
            </div>
            <div class="stat-box-value" id="statsPublic">{{  $communities->where('type', 'public')->count() }}</div>
            <div class="stat-box-label">Komunitas Public</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-icon private">
                <i class="bi bi-shield-lock"></i>
            </div>
            <div class="stat-box-value" id="statsPrivate">{{  $communities->where('type', 'private')->count() }}</div>
            <div class="stat-box-label">Komunitas Private</div>
        </div>
    </div>

    <div class="filter-section">
        <div class="search-wrapper">
           
            <input  type="text" id="searchInput" class="search-input" placeholder="Cari komunitas...">
        </div>
        <select id="typeFilter" class="filter-select">
            <option value="">Semua Tipe</option>
            <option value="public">Public</option>
            <option value="private">Private</option>
        </select>
        <button class="reset-button" id="resetFilter" title="Reset filter">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
    </div>

    <div class="komunitas-grid" id="komunitasGrid">
        @forelse( $communities as $item)
        <div class="komunitas-card" data-type="{{ $item->type }}" 
             data-name="{{ strtolower($item->name) }}"
             data-description="{{ strtolower($item->deskripsi) }}">
            <div class="card-header-section">
                @if($item->logo)
                    <img src="{{ asset('storage/' . $item->logo) }}" alt="{{ $item->name }}" class="komunitas-avatar">
                @else
                    <div class="avatar-placeholder">
                        <i class="bi bi-people-fill"></i>
                    </div>
                @endif
                <div>
                    <span class="type-badge {{ $item->type }}">
                        <i class="bi {{ $item->type == 'public' ? 'bi-globe' : 'bi-shield-lock' }}"></i>
                        {{ $item->type == 'public' ? 'Public' : 'Private' }}
                    </span>
                </div>
            </div>
            
            <div class="card-body-section">
                <h3 class="komunitas-title">{{ $item->name }}</h3>
                <p class="komunitas-description">{{ $item->deskripsi }}</p>
                
                <div class="komunitas-meta">
                    <i class="bi bi-person"></i>
                    <span>
                        @if($item->creator)
                            {{ $item->creator->name }}
                            @if($item->creator->role === 'admin')
                                <small class="text-muted">(Admin)</small>
                            @endif
                        @else
                            <span class="text-muted">Tidak diketahui</span>
                        @endif
                    </span>
                </div>
                
                <div class="komunitas-meta">
                    <i class="bi bi-calendar"></i>
                    <span>{{ $item->created_at->format('d M Y') }}</span>
                </div>
            </div>
            
            <div class="card-footer-section">
                <div class="komunitas-id">#{{ $item->id }}</div>
                <button class="detail-button view-detail" data-id="{{ $item->id }}">
                    <i class="bi bi-eye"></i> Detail
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-people"></i>
            </div>
            <h4 class="empty-title">Belum ada komunitas</h4>
            <p class="empty-description">
                Saat ini belum ada komunitas yang terdaftar dalam sistem.
            </p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.community.partials.script')
@endpush