@extends('layouts.admin')

@push('styles')
    @include('admin.rolerequest.partials.style')
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header-wrapper">
        <h1 class="page-title">
            <i class="bi bi-person-check"></i>
            Permintaan Perubahan Role
        </h1>
        <div class="filter-button-group">
            <button class="filter-btn active" onclick="filterRequests('all')">Semua</button>
            <button class="filter-btn" onclick="filterRequests('pending')">Pending</button>
            <button class="filter-btn" onclick="filterRequests('approved')">Disetujui</button>
            <button class="filter-btn" onclick="filterRequests('rejected')">Ditolak</button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon pending">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $roleRequests->where('status', 'pending')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon approved">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Disetujui</div>
                    <div class="stat-value">{{ $roleRequests->where('status', 'approved')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon rejected">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Ditolak</div>
                    <div class="stat-value">{{ $roleRequests->where('status', 'rejected')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon total">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total Request</div>
                    <div class="stat-value">{{ $roleRequests->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pengguna</th>
                        <th>Role Saat Ini</th>
                        <th>Role Diminta</th>
                        <th>Alasan</th>
                        <th>Tanggal Request</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="requestsTable">
                    @forelse($roleRequests as $request)
                    <tr class="request-row" data-status="{{ $request->status }}" data-id="{{ $request->id }}">
                        <td><strong>#{{ $request->id }}</strong></td>
                        <td>
                            <div class="user-info-cell">
                                @if($request->user->avatar && file_exists(public_path($request->user->avatar)))
                                    <img src="{{ asset($request->user->avatar) }}" alt="{{ $request->user->nama }}" class="user-avatar-cell">
                                @else
                                    <div class="avatar-circle-cell">
                                        {{ strtoupper(substr($request->user->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="user-details">
                                    <div class="user-name">{{ $request->user->nama }}</div>
                                    <div class="user-phone">
                                        <i class="bi bi-telephone"></i>
                                        {{ $request->user->phone }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge-cell {{ $request->user->role }}">
                                {{ strtoupper($request->user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="role-badge-cell pemilik role-arrow">
                                <i class="bi bi-arrow-right"></i>
                                PEMILIK
                            </span>
                        </td>
                        <td>
                            <div class="reason-text" title="{{ $request->reason }}">
                                {{ $request->reason ?: '-' }}
                            </div>
                        </td>
                        <td>
                            <span class="time-text">
                                {{ $request->created_at->diffForHumans() }}
                            </span>
                            <span class="time-date">{{ $request->created_at->format('d M Y H:i') }}</span>
                        </td>
                        <td>
                            @if($request->status == 'pending')
                                <span class="status-badge pending">
                                    <i class="bi bi-clock"></i> Pending
                                </span>
                            @elseif($request->status == 'approved')
                                <span class="status-badge approved">
                                    <i class="bi bi-check"></i> Disetujui
                                </span>
                            @else
                                <span class="status-badge rejected">
                                    <i class="bi bi-x"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($request->status == 'pending')
                                <div class="action-button-group">
                                    <button class="btn-action approve approve-btn" 
                                            data-id="{{ $request->id }}"
                                            data-user="{{ $request->user->nama }}"
                                            data-current-role="{{ $request->user->role }}"
                                            data-phone="{{ $request->user->phone }}"
                                            title="Setujui">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    <button class="btn-action reject reject-btn" 
                                            data-id="{{ $request->id }}"
                                            data-user="{{ $request->user->nama }}"
                                            data-current-role="{{ $request->user->role }}"
                                            data-phone="{{ $request->user->phone }}"
                                            title="Tolak">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @else
                                <button class="btn-action" disabled>
                                    <i class="bi bi-{{ $request->status == 'approved' ? 'check' : 'x' }}"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <p class="empty-text">Tidak ada permintaan perubahan role</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header success">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Setujui Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm">
                @csrf
                <input type="hidden" name="request_id" id="approveRequestId">
                <div class="modal-body">
                    <div class="alert-box info">
                        <i class="bi bi-info-circle" style="font-size: 24px;"></i>
                        <div>
                            Pengguna akan otomatis berubah role dari <strong>penyewa</strong> menjadi <strong>pemilik</strong> setelah disetujui.
                        </div>
                    </div>
                    
                    <div class="user-card">
                        <div class="user-card-header">
                            <div class="user-card-avatar" id="approveAvatar"></div>
                            <div>
                                <div class="user-card-name" id="approveUserName"></div>
                                <div class="user-card-phone">
                                    <i class="bi bi-telephone"></i>
                                    <span id="approveUserPhone"></span>
                                </div>
                            </div>
                        </div>
                        <div class="role-comparison">
                            <div class="role-item">
                                <div class="role-item-label">Role Saat Ini:</div>
                                <span class="role-badge-cell" id="approveCurrentRole"></span>
                            </div>
                            <div class="role-item">
                                <div class="role-item-label">Akan Menjadi:</div>
                                <span class="role-badge-cell pemilik">PEMILIK</span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-center mb-0">
                        <strong>Apakah Anda yakin ingin menyetujui permintaan ini?</strong>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check"></i> Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header danger">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle me-2"></i>Tolak Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                @csrf
                <input type="hidden" name="request_id" id="rejectRequestId">
                <div class="modal-body">
                    <div class="alert-box warning">
                        <i class="bi bi-exclamation-triangle" style="font-size: 24px;"></i>
                        <div>
                            Role pengguna akan tetap sebagai <strong id="rejectCurrentRoleText">penyewa</strong>.
                        </div>
                    </div>
                    
                    <div class="user-card">
                        <div class="user-card-header">
                            <div class="user-card-avatar" id="rejectAvatar"></div>
                            <div>
                                <div class="user-card-name" id="rejectUserName"></div>
                                <div class="user-card-phone">
                                    <i class="bi bi-telephone"></i>
                                    <span id="rejectUserPhone"></span>
                                </div>
                            </div>
                        </div>
                        <div class="role-comparison">
                            <div class="role-item">
                                <div class="role-item-label">Role Saat Ini:</div>
                                <span class="role-badge-cell" id="rejectCurrentRole"></span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-center mb-0">
                        <strong>Apakah Anda yakin ingin menolak permintaan ini?</strong>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger-modal">
                        <i class="bi bi-x"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('admin.rolerequest.partials.script')
@endpush
