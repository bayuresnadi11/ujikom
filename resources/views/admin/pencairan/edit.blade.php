@extends('layouts.admin')

@push('styles')
    @include('admin.pencairan.partials.style')
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-title">
                <i class="fas fa-edit"></i>
                Edit Status Pencairan Dana
            </h2>
            <a href="{{ route('admin.pencairan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pencairan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pencairan.update', $withdrawal->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- User Information (Readonly) -->
                        <div class="mb-4">
                            <h6 class="section-title">Informasi Pengaju</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Pengaju</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $withdrawal->user->name }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Withdrawal Details (Readonly) -->
                        <div class="mb-4">
                            <h6 class="section-title">Detail Penarikan</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Penarikan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control bg-light" 
                                                   value="{{ number_format($withdrawal->amount, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bank</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $withdrawal->bank_name }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Rekening</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $withdrawal->account_number }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemilik Rekening</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $withdrawal->account_holder_name }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Update Section -->
                        <div class="mb-4">
                            <h6 class="section-title">Update Status</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="pending" {{ $withdrawal->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $withdrawal->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="processed" {{ $withdrawal->status == 'processed' ? 'selected' : '' }}>Diproses (Telah Ditransfer)</option>
                                            <option value="rejected" {{ $withdrawal->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bukti Foto -->
                            <div class="mb-3">
                                <label for="photo" class="form-label">Upload Bukti Transfer (Opsional)</label>
                                <input type="file" class="form-control" id="photo" name="photo" 
                                       accept="image/*">
                                <small class="text-muted">Format: jpeg, png, jpg, gif (max: 2MB)</small>
                                
                                @if($withdrawal->photo)
                                <div class="mt-2">
                                    <label class="form-label">Bukti Saat Ini</label>
                                    <div>
                                        <a href="{{ asset('storage/' . $withdrawal->photo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Lihat Bukti
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Rejection Reason (Conditional) -->
                            <div id="rejectionReasonSection" style="display: {{ $withdrawal->status == 'rejected' ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="rejection_reason" class="form-label">Alasan Penolakan *</label>
                                    <textarea name="rejection_reason" id="rejection_reason" 
                                              class="form-control" rows="3" 
                                              {{ $withdrawal->status == 'rejected' ? 'required' : '' }}>{{ old('rejection_reason', $withdrawal->rejection_reason) }}</textarea>
                                    <small class="text-muted">Wajib diisi jika status ditolak</small>
                                </div>
                            </div>

                            <!-- Processed Info (Auto-filled when processed) -->
                            <div id="processedInfo" class="alert alert-info" style="display: {{ $withdrawal->status == 'processed' ? 'block' : 'none' }};">
                                <i class="fas fa-info-circle"></i>
                                Jika status diubah menjadi "Diproses", sistem akan otomatis mencatat:
                                <ul class="mb-0 mt-2">
                                    <li>Tanggal proses: {{ now()->translatedFormat('d F Y H:i') }}</li>
                                    <li>Diproses oleh: {{ Auth::user()->name }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.pencairan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Current Status -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Saat Ini</h5>
                </div>
                <div class="card-body">
                    <div class="status-summary">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge {{ $withdrawal->getStatusBadgeClass() }} me-2 status-badge-lg">
                                {{ $withdrawal->getStatusLabel() }}
                            </span>
                            <span class="text-muted small">#{{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="text-muted small">Diajukan</div>
                                    <div>{{ $withdrawal->created_at->translatedFormat('d F Y H:i') }}</div>
                                </div>
                            </div>
                            
                            @if($withdrawal->processed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="text-muted small">Diproses</div>
                                    <div>{{ $withdrawal->processed_at->translatedFormat('d F Y H:i') }}</div>
                                    <div class="text-muted small">Oleh: {{ $withdrawal->processedBy->name ?? '-' }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($withdrawal->rejection_reason)
                        <div class="alert alert-danger mt-3">
                            <h6><i class="fas fa-ban me-2"></i>Ditolak</h6>
                            <p class="mb-0">{{ $withdrawal->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.pencairan.update', $withdrawal->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-info btn-sm" onclick="return confirm('Setujui pencairan ini?')">
                                <i class="fas fa-check-circle"></i> Setujui
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.pencairan.update', $withdrawal->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="processed">
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tandai sebagai sudah ditransfer?')">
                                <i class="fas fa-check-double"></i> Tandai Sudah Ditransfer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const rejectionSection = document.getElementById('rejectionReasonSection');
        const rejectionReason = document.getElementById('rejection_reason');
        const processedInfo = document.getElementById('processedInfo');

        function toggleFields() {
            if (statusSelect.value === 'rejected') {
                rejectionSection.style.display = 'block';
                rejectionReason.required = true;
            } else {
                rejectionSection.style.display = 'none';
                rejectionReason.required = false;
            }

            if (statusSelect.value === 'processed') {
                processedInfo.style.display = 'block';
            } else {
                processedInfo.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', toggleFields);
        
        // Initial toggle
        toggleFields();
    });
</script>
@endpush