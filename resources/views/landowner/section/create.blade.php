@extends('layouts.main', ['title' => 'Tambah Lapangan Baru'])

@push('styles')
    @include('landowner.section.partials.section-style')
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.section-lapangan.index', ['venue_id' => request('venue_id')]) }}" class="logo">
                    <i class="fas fa-arrow-left logo-icon"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Tambah Lapangan</h1>
                <p class="page-subtitle">Venue: {{ $venue->venue_name }}</p>
            </section>

            <div class="section-list">
                <div class="section-card" style="padding: 20px; border-top: none;">
                    <form action="{{ route('landowner.section-lapangan.store') }}" method="POST" id="sectionForm">
                        @csrf
                        <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-signature"></i>
                                Nama Lapangan *
                            </label>
                            <input type="text" class="form-control @error('section_name') is-invalid @enderror" 
                                   name="section_name" id="section_name" value="{{ old('section_name') }}"
                                   placeholder="Contoh: Lapangan A">
    @endpush
