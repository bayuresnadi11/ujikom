@extends('layouts.main', ['title' => 'Draft Undangan - ' . $community->name])
@php
    if (!function_exists('getInitials')) {
        function getInitials($name) {
            if (!$name) return '??';
            $parts = explode(' ', $name);
            if (count($parts) >= 2) {
                return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
            }
            return strtoupper(substr($name, 0, 2));
        }
    }

    if (!function_exists('getAvatarColor')) {
        function getAvatarColor($name) {
            $colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
            $hash = crc32($name);
            return $colors[abs($hash) % count($colors)];
        }
    }
@endphp

@push('styles')
<style>
    /* ================= VARIABLES & BASE ================= */
    :root {
        --primary: #0A5C36;
        --secondary: #2ecc71;
        --danger: #e74c3c;
        --text: #1A3A27;
        --text-light: #6C757D;
        --light: #F8F9FA;
        --border: #E6F7EF;
        --radius-md: 8px;
        --radius-lg: 10px;
    }

    body {
        background-color: var(--light);
    }

    .mobile-container {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
        max-width: 480px;
        margin: 0 auto;
        position: relative;
    }

    /* ================= DRAFT LIST ================= */
    .draft-container {
        padding: 20px;
        padding-bottom: 100px; /* Space for footer */
    }

    .draft-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .draft-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }

    .draft-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .draft-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: white;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        text-transform: uppercase;
        flex-shrink: 0;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .initials-placeholder {
        background-color: var(--primary);
    }

    .user-details h4 {
        font-size: 14px;
        margin: 0;
        color: var(--text);
    }

    .user-details p {
        font-size: 12px;
        margin: 0;
        color: var(--text-light);
    }

    .remove-btn {
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        font-size: 16px;
        padding: 8px;
    }

    .remove-btn:hover {
        color: var(--danger);
    }

    /* ================= FOOTER ACTION ================= */
    .action-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        padding: 16px;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
    }

    @media (min-width: 480px) {
        .action-footer {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
        }
    }

    .send-btn {
        width: 100%;
        background: var(--primary);
        color: white;
        border: none;
        padding: 14px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .send-btn:hover {
        background: #084c2d;
    }

    .send-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-light);
    }

    /* ================= LOADING OVERLAY ================= */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        color: white;
        text-align: center;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    .loading-text {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .loading-subtext {
        font-size: 12px;
        opacity: 0.8;
        max-width: 250px;
        line-height: 1.5;
        padding: 0 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="mobile-container">
    @include('layouts.header')

    <div class="main-content" style="padding-top: 80px;">
        <div class="draft-container">
            <div class="draft-header">
                <a href="{{ route('buyer.communities.invite', $community->id) }}" style="color: var(--text); font-size: 18px;">
                    <i class="fas fa-times"></i>
                </a>
                <span class="draft-title">Draft Undangan</span>
            </div>

            <div class="search-box mb-3" style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #aaa;"></i>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Cari di draft undangan" 
                    class="form-control" 
                    style="padding-left: 36px; border-radius: 8px; border: 1px solid #ddd; width: 100%; padding-top: 10px; padding-bottom: 10px;"
                >
            </div>

            <div id="draftList" class="draft-list">
                @forelse($users as $user)
                    <div class="draft-item" id="user-{{ $user->id }}">
                        <div class="user-info">
                            @if($user->avatar && !str_contains($user->avatar, 'default-avatar.png'))
                                <img 
                                    src="{{ asset('storage/' . $user->avatar) }}" 
                                    class="user-avatar"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="user-avatar initials-placeholder" style="background-color: {{ getAvatarColor($user->name) }}; display: none;">
                                    {{ getInitials($user->name) }}
                                </div>
                            @else
                                <div class="user-avatar initials-placeholder" style="background-color: {{ getAvatarColor($user->name) }}">
                                    {{ getInitials($user->name) }}
                                </div>
                            @endif
                            <div class="user-details">
                                <h4>{{ $user->name }}</h4>
                                <p>{{ $user->username ? '@'.$user->username : $user->email }} @if($user->level) • {{ $user->level }} @endif</p>
                            </div>
                        </div>
                        <button class="remove-btn" onclick="removeUser({{ $user->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-users-slash" style="font-size: 40px; margin-bottom: 12px;"></i>
                        <p>Belum ada user di draft</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($users->count() > 0)
    <div class="action-footer" id="actionFooter">
        <form action="{{ route('buyer.communities.invite.send', $community->id) }}" method="POST" id="inviteForm">
            @csrf
            <button type="submit" class="send-btn">
                Kirim Undangan
            </button>
        </form>
    </div>
    @endif

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Memproses...</div>
        <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function removeUser(userId) {
        if(!confirm('Hapus user dari draft?')) return;

        const item = document.getElementById(`user-${userId}`);
        item.style.opacity = '0.5';

        try {
            const baseUrl = "{{ route('buyer.communities.invite.draft.remove', ['community' => $community->id, 'userId' => 'DISCARD_ID']) }}";
            const url = baseUrl.replace('DISCARD_ID', userId);
            
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                item.remove();
                if (data.count === 0) {
                    document.getElementById('draftList').innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-users-slash" style="font-size: 40px; margin-bottom: 12px;"></i>
                            <p>Belum ada user di draft</p>
                        </div>
                    `;
                    const footer = document.getElementById('actionFooter');
                    if (footer) footer.remove();
                }
            }
        } catch (error) {
            console.error('Error:', error);
            item.style.opacity = '1';
            alert('Gagal menghapus user');
        }
    }
    // ================= SEARCH LOGIC =================
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.draft-item');
        
        items.forEach(item => {
            const name = item.querySelector('h4').textContent.toLowerCase();
            const details = item.querySelector('p').textContent.toLowerCase();
            
            if (name.includes(query) || details.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        // Show empty search state if all items are hidden
        const visibleItems = Array.from(items).filter(item => item.style.display !== 'none');
        const emptyState = document.querySelector('.empty-state');
        
        if (visibleItems.length === 0 && items.length > 0) {
            if (!document.getElementById('searchEmptyState')) {
                const searchEmpty = document.createElement('div');
                searchEmpty.id = 'searchEmptyState';
                searchEmpty.className = 'empty-state';
                searchEmpty.innerHTML = `
                    <i class="fas fa-search" style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;"></i>
                    <p>User "${e.target.value}" tidak ditemukan di draft</p>
                `;
                document.getElementById('draftList').appendChild(searchEmpty);
            }
        } else {
            const searchEmpty = document.getElementById('searchEmptyState');
            if (searchEmpty) searchEmpty.remove();
        }
    });

    // ================= LOADING LOGIC =================
    document.getElementById('inviteForm')?.addEventListener('submit', function() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    });
</script>
@endpush
