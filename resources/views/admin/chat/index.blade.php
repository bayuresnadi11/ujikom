@extends('layouts.admin')

@section('content')
@include('admin.chat.partials.chat-style')

<div class="admin-container fade-page">
    <div class="admin-header fade-down">
        <h1 class="admin-title">
            <i class="bi bi-chat-dots-fill me-3"></i> Pesan
        </h1>
        <p class="admin-subtitle">
            Kelola pesan dari penyewa dan pemilik lapangan.
        </p>
    </div>

    <div class="mobile-container">
        <!-- Search Bar -->
        @if(!isset($conversationId))
        <div class="search-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       class="search-input" 
                       placeholder="Cari chat atau user..." 
                       wire:model="search"
                       id="searchInput">
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <main class="main-content">
            <!-- Tabs -->
            @if(!isset($conversationId))
            <div class="chat-tabs">
                <button class="tab-btn {{ !isset($conversationId) ? 'active' : '' }}" 
                        onclick="switchToList()">
                    <i class="fas fa-comment-dots"></i>
                    Pesan
                    @if(auth()->user()->unreadMessagesCount() > 0)
                        <span class="badge">{{ auth()->user()->unreadMessagesCount() }}</span>
                    @endif
                </button>
                <button class="tab-btn {{ isset($conversationId) ? 'active' : '' }}" 
                        id="chatBoxTab"
                        style="display: {{ isset($conversationId) ? 'block' : 'none' }}">
                    <i class="fas fa-comments"></i>
                    Chat
                </button>
            </div>
            @endif

            <!-- Tab Content: Chat List -->
            <div id="chats-tab" class="tab-content {{ !isset($conversationId) ? 'active' : '' }}">
                @livewire('chat.chat-list')
            </div>

            <!-- Tab Content: Chat Box -->
            @if(isset($conversationId))
            <div id="chatbox-tab" class="tab-content active">
                <div class="chat-box-wrapper">
                    @livewire('chat.chat-box', ['conversationId' => $conversationId])
                </div>
            </div>
            @endif
        </main>
    </div>
</div>

<script>
    function switchToList() {
        window.location.href = "{{ route('chat.index') }}";
    }
</script>
@endsection
