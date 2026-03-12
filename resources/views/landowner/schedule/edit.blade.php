@extends('layouts.main', ['title' => 'Edit Jadwal'])

@push('styles')
    @include('landowner.schedule.partials.schedule-style')
@endpush

@section('content')
    <div class="mobile-container">
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.schedule.index', ['venue_id' => $schedule->venueSection->venue_id]) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>

            </div>
        </header>

        <main class="main-content">
            <div class="form-container">
                <form action="{{ route('landowner.schedule.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="venue-selector-card" style="margin: 0 0 20px 0;">
                        <div class="venue-selector-header">
                            <i class="fas fa-edit venue-selector-icon"></i>
                            <h3 class="venue-selector-title">Perbarui Jadwal</h3>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Detail Jadwal</label>
                            <div class="schedule-details-box">
                                <div class="schedule-details-item">
                                    <span class="schedule-details-label">Lapangan</span>
                                    <span class="schedule-details-value">{{ $schedule->venueSection->section_name }}</span>
                                </div>
                                <div class="schedule-details-item">
                                    <span class="schedule-details-label">Tanggal</span>
                                    <span class="schedule-details-value">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d F Y') }}</span>
                                </div>
                                <div class="schedule-details-item">
                                    <span class="schedule-details-label">Jam</span>
                                    <span class="schedule-details-value">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Harga Sewa (Rp)</label>
                            <input type="number" name="rental_price" class="form-control" required min="0" value="{{ $schedule->rental_price }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="available" class="form-control">
                                <option value="1" {{ $schedule->available == 1 ? 'selected' : '' }}>Tersedia</option>
                                <option value="0" {{ $schedule->available == 0 ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
                  @include('layouts.bottom-nav')

    @endsection
