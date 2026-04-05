@extends('layouts.main', ['title' => 'Komunitas'])

@push('styles')
@include('buyer.communities.partials.communities-style')
<style>
/* =========================
   GLOBAL
========================= */
.container {
    padding: 20px 16px;
    font-family: 'Inter', sans-serif;
}

/* =========================
   SECTION TITLE
========================= */
.section-title {
    font-size: 22px;
    font-weight: 700;
    color: #0a5c36;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    color: #0a5c36;
    font-size: 20px;
}

/* =========================
   MODAL OPTIONS WRAPPER
========================= */
.modal-options {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* =========================
   OPTION CARD
========================= */
.modal-option {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    background: #ffffff;
    border-radius: 14px;
    text-decoration: none;
    color: #333;
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    transition: all 0.25s ease;
}

.modal-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(0,0,0,0.08);
}

/* =========================
   ICON LEFT
========================= */
.option-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(10, 92, 54, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.option-icon i {
    font-size: 20px;
    color: #0a5c36;
}

/* =========================
   TEXT CONTENT
========================= */
.option-content {
    flex: 1;
}

.option-content h3 {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    color: #222;
}

.option-content p {
    font-size: 13px;
    margin: 4px 0 0;
    color: #666;
}

/* =========================
   ARROW RIGHT
========================= */
.option-arrow {
    font-size: 14px;
    color: #aaa;
}

/* =========================
   MOBILE OPTIMIZATION
========================= */
@media (max-width: 480px) {
    .modal-option {
        padding: 14px;
    }

    .option-icon {
        width: 42px;
        height: 42px;
    }

    .option-content h3 {
        font-size: 15px;
    }

    .option-content p {
        font-size: 12px;
    }
}
</style>
@endpush

@section('content')
<div class="mobile-container" id="mobileContainer">
    @include('layouts.header')

    <main class="main-content">
        <div class="container" style="max-width:600px;margin:auto;">

            <h2 class="section-title" style="margin-bottom:20px;">
                <i class="fas fa-users"></i>
                Komunitas
            </h2>

            <div class="modal-options">

                <a href="{{ route('buyer.communities.join') }}" class="modal-option">
                    <div class="option-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="option-content">
                        <h3>Cari & Gabung Komunitas</h3>
                        <p>Temukan komunitas yang sesuai dengan minat Anda</p>
                    </div>
                    <i class="fas fa-chevron-right option-arrow"></i>
                </a>

                <a href="{{ route('buyer.communities.create') }}" class="modal-option">
                    <div class="option-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="option-content">
                        <h3>Buat Komunitas Baru</h3>
                        <p>Buat komunitas Anda sendiri dan kelola anggota</p>
                    </div>
                    <i class="fas fa-chevron-right option-arrow"></i>
                </a>

            </div>
        </div>
    </main>

    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
    @include('buyer.communities.partials.communities-script')
@endpush