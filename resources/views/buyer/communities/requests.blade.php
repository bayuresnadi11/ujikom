@extends('layouts.main', ['title' => 'Komunitas'])

@push('styles')
    @include('buyer.communities.partials.communities-style')
@endpush

@section('content')
<div class="mobile-container">
    @include('layouts.header')

    <main class="main-content">
        <div class="join-header">
            <a href="{{ route('buyer.communities.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Permintaan Bergabung</h1>
        </div>
        <h2>Komunitas: {{ $community->name }}</h2>

        @forelse ($requests as $req)
    <div class="request-row">
        <div class="request-col name">
            {{ $req->user->name }}
        </div>

        <div class="request-col action">
            <form method="POST"
                  action="{{ route('buyer.communities.requests.approve', $req->id) }}">
                @csrf
                <button class="btn-approve">Terima</button>
            </form>
        </div>

        <div class="request-col action">
            <form method="POST"
                  action="{{ route('buyer.communities.requests.reject', $req->id) }}">
                @csrf
                <button class="btn-reject">Tolak</button>
            </form>
        </div>
    </div>
@empty
    <p class="empty-text">Tidak ada request</p>
@endforelse

    </main>
    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
    @include('buyer.communities.partials.communities-script')
@endpush