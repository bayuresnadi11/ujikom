@extends('layouts.main', ['title' => 'Manajemen Cashier'])

@push('styles')
    @include('landowner.cashier.partials.cashier-style')
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Cashier</h1>
                <p class="page-subtitle">Kelola akun cashier untuk venue Anda</p>
            </section>

            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $totalCashiers }}</div>
                        <div class="stat-label">Total Cashier</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $venues }}</div>
                        <div class="stat-label">Total Venue</div>
                    </div>
                </div>
            </div>

            {{-- Notifikasi ditangani otomatis oleh komponen toast-alert di layout --}}

            @if(!$cashiers->isEmpty())
            <!-- Action Bar -->
            <div class="action-bar">
                <a href="{{ route('landowner.cashier.create') }}" class="btn-add">
                    <i class="fas fa-plus-circle"></i> Tambah Cashier Baru
                </a>
            </div>
            @endif

            <!-- Cashier List -->
            @if($cashiers->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-users-slash empty-state-icon"></i>
                    <h3 class="empty-state-title">Belum Ada Cashier</h3>
                    <p class="empty-state-desc">Tambahkan akun cashier untuk membantu mengelola venue Anda.</p>
                    <a href="{{ route('landowner.cashier.create') }}" class="btn-primary" style="margin-top: 10px;">
                        <i class="fas fa-plus"></i> Tambah Cashier
                    </a>
                </div>
            @else
                <div class="cashier-list" id="cashierList">
                    {{-- Me-looping semua data cashier untuk ditampilkan sebagai kartu/list item --}}
                    @foreach($cashiers as $c)
                        <div class="cashier-card" data-name="{{ strtolower($c->name) }}">
                            <div class="cashier-info">
                                <div class="cashier-avatar">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div>
                                <div class="cashier-details">
                                    <h3 class="cashier-name">{{ $c->name }}</h3>
                                    <div class="cashier-phone">
                                        <i class="fas fa-phone-alt"></i> {{ $c->phone }}
                                    </div>
                                    <div class="cashier-meta">
                                        <span><i class="fas fa-calendar-alt"></i> Join: {{ $c->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="cashier-actions">
                                <a href="{{ route('landowner.cashier.edit', $c->id) }}" class="action-btn btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('landowner.cashier.destroy', $c->id) }}" method="POST" 
                                        onsubmit="event.preventDefault(); confirmDelete('Hapus Cashier?', 'Yakin ingin menghapus cashier ini?').then(res => { if(res.isConfirmed) this.submit(); })" 
                                        style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>

    <!-- Scripts -->
    <script>
        // Logika Javascript untuk mencari (filter) data cashier berdasarkan nama tanpa me-reload halaman
        function searchCashier() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const cards = document.getElementsByClassName('cashier-card');

            // Cek setiap card, lalu hide yang tidak cocok dengan kata kunci
            for (let i = 0; i < cards.length; i++) {
                const name = cards[i].getAttribute('data-name');
                if (name.indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
        
        function showNotification() {
            // Implement notification logic
            alert('Fitur notifikasi akan segera hadir');
        }
    </script>
@endsection