<!-- resources/views/buyer/chat/index.blade.php -->
@extends('layouts.main', ['title' => 'Chat - SewaLap'])

@include('landowner.chat.partials.chat-style')

@section('content')
<div class="mobile-container">
    <!-- Header -->
    <header class="mobile-header">
        <div class="header-top">
            <h1 style="margin: 0; font-size: 18px; font-weight: 700; color: white; letter-spacing: 0.5px;">CHAT</h1>
            
            <div class="header-actions">
                <!-- Only show back button if inside a conversation -->
                @if(isset($conversationId))
                    <button class="header-icon" onclick="window.location.href='{{ route('chat.index') }}'">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                @else
                    <button class="header-icon" onclick="window.location.href='{{ route('landowner.home') }}'">
                        <i class="fas fa-home"></i>
                    </button>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content {{ isset($conversationId) ? 'chat-active' : '' }}">
        {{-- Logika Kondisional: Menukar view dari List Chat menjadi Room Chat spesifik jika ID conversation terdeteksi --}}
        @if(!isset($conversationId))
            <!-- Chat List View -->
            <div id="chats-tab" class="tab-content" style="display: block;">
                {{-- Data dirender menggunakan Livewire secara Async untuk update real-time --}}
                @livewire('chat.chat-list')
            </div>
        @else
            <!-- Chat Box View -->
            <div id="chatbox-tab" class="tab-content" style="display: block; flex: 1; height: 100%;">
                @livewire('chat.chat-box', ['conversationId' => $conversationId])
            </div>
        @endif
    </main>

</div>

<script>
    // Simple script to handle view logic if needed
    function switchToList() {
        window.location.href = "{{ route('chat.index') }}";
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto scroll on load
        const box = document.getElementById('messagesContainer');
        if(box) box.scrollTop = box.scrollHeight;
    });
</script>
@endsection