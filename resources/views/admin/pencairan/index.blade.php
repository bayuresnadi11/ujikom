@extends('layouts.admin')

@push('styles')
    @include('admin.pencairan.partials.style')
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-money-check-alt"></i>
            Pencairan Dana
        </h2>
    </div>

    <!-- Filter Section -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.pencairan.index') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="status" class="form-label">Filter Status</label>
                            <select name="status" id="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status ({{ $statusCounts['all'] }})</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending ({{ $statusCounts['pending'] }})</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui ({{ $statusCounts['approved'] }})</option>
                                <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diproses ({{ $statusCounts['processed'] }})</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak ({{ $statusCounts['rejected'] }})</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawal Requests Cards -->
    <div class="row" id="withdrawalCards">
        @forelse($withdrawals as $withdrawal)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card withdrawal-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge {{ $withdrawal->getStatusBadgeClass() }} status-badge">
                            {{ $withdrawal->getStatusLabel() }}
                        </span>
                    </div>
                    <div class="text-muted small">
                        #{{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="mb-3">
                        <div class="user-info d-flex align-items-center mb-2">
                            <div class="user-icon me-2">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </div>
                            <div>
                                <strong>{{ $withdrawal->user->name }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="amount-display mb-3">
                        <div class="text-muted small">Jumlah Penarikan</div>
                        <h4 class="amount-value">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</h4>
                    </div>

                    <div class="bank-info mb-3">
                        <div class="text-muted small">Rekening Tujuan</div>
                        <div class="bank-details">
                            <div><i class="fas fa-university me-2"></i> {{ $withdrawal->bank_name }}</div>
                            <div><i class="fas fa-credit-card me-2"></i> {{ $withdrawal->account_number }}</div>
                            <div><i class="fas fa-user-tag me-2"></i> {{ $withdrawal->account_holder_name }}</div>
                        </div>
                    </div>

                    @if($withdrawal->photo)
                    <div class="photo-preview mb-3">
                        <div class="text-muted small mb-1">Bukti Transfer</div>
                        <a href="{{ asset('storage/' . $withdrawal->photo) }}" target="_blank" class="photo-link">
                            <img src="{{ asset('storage/' . $withdrawal->photo) }}" alt="Bukti Transfer" 
                                 class="img-thumbnail photo-thumbnail">
                            <div class="photo-overlay">
                                <i class="fas fa-search-plus"></i> Lihat
                            </div>
                        </a>
                    </div>
                    @endif

                    @if($withdrawal->rejection_reason)
                    <div class="rejection-reason mb-3">
                        <div class="text-muted small">Alasan Penolakan</div>
                        <div class="alert alert-danger p-2 mb-0">
                            {{ $withdrawal->rejection_reason }}
                        </div>
                    </div>
                    @endif

                    <div class="timestamps">
                        <div class="text-muted small">Tanggal Pengajuan</div>
                        <div>{{ $withdrawal->created_at->translatedFormat('d F Y H:i') }}</div>
                        
                        @if($withdrawal->processed_at)
                        <div class="text-muted small mt-2">Tanggal Diproses</div>
                        <div>{{ $withdrawal->processed_at->translatedFormat('d F Y H:i') }}</div>
                        <div class="text-muted small">Oleh: {{ $withdrawal->processedBy->name ?? '-' }}</div>
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-grid">
                        <a href="{{ route('admin.pencairan.edit', $withdrawal->id) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada data pencairan dana yang ditemukan.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($withdrawals->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $withdrawals->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto submit filter on change
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endpush