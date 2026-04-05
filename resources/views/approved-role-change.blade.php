@extends('layouts.main', ['title' => 'Pengajuan Disetujui - SewaLap'])

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .approval-container {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 100%;
        padding: 48px 32px;
        text-align: center;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        animation: bounce 0.6s ease;
    }

    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .success-icon i {
        font-size: 48px;
        color: white;
    }

    .approval-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .approval-subtitle {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .info-card {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        text-align: left;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #d1d5db;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    .info-value {
        font-size: 14px;
        color: #1f2937;
        font-weight: 600;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .logout-btn {
        width: 100%;
        padding: 16px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .logout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .logout-btn:active {
        transform: translateY(0);
    }

    .note {
        margin-top: 24px;
        padding: 16px;
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        text-align: left;
    }

    .note-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 8px;
    }

    .note-text {
        font-size: 13px;
        color: #92400e;
        line-height: 1.5;
    }

    @media (max-width: 640px) {
        .approval-container {
            padding: 32px 24px;
        }

        .approval-title {
            font-size: 24px;
        }

        .approval-subtitle {
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="approval-container">
    <div class="success-icon">
        <i class="fas fa-check"></i>
    </div>

    <h1 class="approval-title">Selamat! 🎉</h1>
    <p class="approval-subtitle">
        Pengajuan Anda untuk menjadi Pemilik Lapangan telah <strong>disetujui</strong> oleh admin. 
        Role Anda telah diubah.
    </p>

    <div class="info-card">
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value">{{ auth()->user()->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Role Sebelumnya</span>
            <span class="info-value">Buyer</span>
        </div>
        <div class="info-row">
            <span class="info-label">Role Sekarang</span>
            <span class="badge">Landowner</span>
        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            Logout & Login Kembali
        </button>
    </form>

    <div class="note">
        <div class="note-title">
            <i class="fas fa-info-circle"></i>
            Catatan Penting
        </div>
        <p class="note-text">
            Silakan logout dan login kembali untuk mengakses fitur Pemilik Lapangan. 
            Setelah login, Anda dapat mulai mengelola lapangan Anda.
        </p>
    </div>
</div>
@endsection
