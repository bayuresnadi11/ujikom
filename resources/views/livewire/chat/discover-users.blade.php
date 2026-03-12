<!-- resources/views/livewire/chat/discover-users.blade.php -->
<div>
    <div class="discover-section">
        <div class="section-title">
            <i class="fas fa-compass"></i>
            Mulai Percakapan Baru
        </div>

        <!-- Filter -->
        <div class="quick-actions" style="margin-bottom: 20px;">
            <button class="quick-action {{ $userType === 'all' ? 'active' : '' }}" 
                    wire:click="$set('userType', 'all')">
                <i class="fas fa-users"></i>
                Semua
            </button>
            <button class="quick-action {{ $userType === 'buyer' ? 'active' : '' }}" 
                    wire:click="$set('userType', 'buyer')">
                <i class="fas fa-user"></i>
                Penyewa
            </button>
            <button class="quick-action {{ $userType === 'landowner' ? 'active' : '' }}" 
                    wire:click="$set('userType', 'landowner')">
                <i class="fas fa-store"></i>
                Pemilik
            </button>
            <button class="quick-action {{ $userType === 'admin' ? 'active' : '' }}" 
                    wire:click="$set('userType', 'admin')">
                <i class="fas fa-shield-alt"></i>
                Admin
            </button>
        </div>

        <!-- User List -->
        <div class="discover-grid">
            @if(mb_strlen(trim($search)) === 1)
                <!-- Ketik minimal 2 karakter -->
                <div class="empty-state active">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="empty-title">
                        Ketik Minimal 2 Karakter
                    </div>
                    <div class="empty-desc">
                        Tambahkan 1 huruf lagi untuk mulai mencari
                    </div>
                </div>

            @elseif(count($users) > 0)
                <!-- Show users if found -->
                @foreach($users as $user)
                    <div class="discover-card" wire:click="startChat({{ $user->id }})">
                        <div class="discover-icon" 
                             style="background: linear-gradient(135deg, 
                                    {{ $user->role === 'admin' ? '#f39c12, #e67e22' : 
                                       ($user->role === 'landowner' ? '#0A5C36, #27AE60' : '#3498db, #2980b9') }});">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" 
                                     alt="{{ $user->name }}"
                                     style="width: 100%; height: 100%; border-radius: 12px; object-fit: cover;">
                            @else
                                <i class="fas fa-user" style="color: white;"></i>
                            @endif
                        </div>
                        <div class="discover-info">
                            <div class="discover-title">
                                {{ $user->name }}
                                @if($user->role === 'admin')
                                    <i class="fas fa-shield-alt" style="color: var(--warning); font-size: 12px; margin-left: 4px;"></i>
                                @endif
                            </div>
                            <div class="discover-subtitle">
                                @if($user->role === 'admin')
                                    Administrator
                                @elseif($user->role === 'landowner')
                                    Pemilik Lapangan
                                @elseif($user->role === 'buyer')
                                    Penyewa
                                @else
                                    Pengguna
                                @endif
                            </div>
                            @if($user->phone)
                                <div class="discover-phone">
                                    <i class="fas fa-phone" style="font-size: 10px;"></i>
                                    {{ $user->phone }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @elseif(mb_strlen(trim($search)) >= 2)
                <!-- hasil kosong -->
                <div class="empty-state active">
                    <div class="empty-icon">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="empty-title">
                        Pengguna Tidak Ditemukan
                    </div>
                    <div class="empty-desc">
                        Tidak ada pengguna dengan kata kunci "<strong>{{ $search }}</strong>"
                    </div>
                </div>

            @else
                <!-- search kosong -->
                <div class="empty-state active">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="empty-title">
                        Mulai Pencarian
                    </div>
                    <div class="empty-desc">
                        Ketik nama pengguna untuk mulai mencari
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .discover-subtitle {
            font-size: 12px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }
        
        .discover-phone {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</div>