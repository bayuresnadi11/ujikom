<!-- resources/views/buyer/chat/index.blade.php -->
@extends('layouts.main', ['title' => 'Chat - SewaLap'])

@include('buyer.chat.partials.chat-style')

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
                    <button class="header-icon" onclick="window.location.href='{{ route('buyer.home') }}'">
                        <i class="fas fa-home"></i>
                    </button>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content {{ isset($conversationId) ? 'chat-active' : '' }}">
        @if(!isset($conversationId))
            <!-- Chat List View -->
            <div id="chats-tab" class="tab-content" style="display: block;">
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
@endsection

@include('buyer.chat.partials.chat-script')
