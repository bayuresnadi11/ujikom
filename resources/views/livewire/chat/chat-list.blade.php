<!-- resources/views/livewire/chat/chat-list.blade.php -->
<div>
    <!-- Global Search (For both lists) -->
    <div class="search-section" style="padding-bottom: 0;">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" 
                   class="search-input" 
                   placeholder="{{ $activeTab === 'chats' ? 'Cari percakapan...' : 'Cari pengguna...' }}" 
                   wire:model.live.debounce.300ms="search">
            @if($search)
                <i class="fas fa-times" style="color: #9ca3af; cursor: pointer;" wire:click="$set('search', '')"></i>
            @endif
        </div>
    </div>

    @if($activeTab === 'chats')
        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="quick-action {{ $filter === 'all' ? 'active' : '' }}" wire:click="$set('filter', 'all')">
                <i class="fas fa-comments"></i> Semua
            </button>
            <button class="quick-action {{ $filter === 'unread' ? 'active' : '' }}" wire:click="$set('filter', 'unread')">
                <i class="fas fa-envelope"></i> Belum Dibaca
            </button>
            <button class="quick-action {{ $filter === 'group' ? 'active' : '' }}" wire:click="$set('filter', 'group')">
                <i class="fas fa-users"></i> Grup
            </button>
            <button class="quick-action {{ $filter === 'support' ? 'active' : '' }}" wire:click="$set('filter', 'support')">
                <i class="fas fa-headset"></i> Support  
            </button>
        </div>

        <!-- Chats List -->
        <div class="chats-list">
            @forelse($conversations as $conv)
                @php
                    $isCommunity = $conv->type === 'community';
                    $otherUser = $conv->getOtherUser(auth()->id());
                    $unreadCount = $conv->unreadCount(auth()->id());
                    $lastMsg = $conv->lastMessage;
                @endphp

                <div class="chat-item {{ $unreadCount > 0 ? 'unread' : '' }}" 
                     wire:click="$dispatch('conversation-selected', { id: {{ $conv->id }} })"
                     onclick="window.location.href='{{ route('chat.show', $conv->id) }}'">
                    
                    <div class="chat-avatar">
                        <div class="avatar-img">
                            @if($isCommunity)
                                {{-- Untuk grup komunitas --}}
                                @if($otherUser && $otherUser->logo)
                                    <img src="{{ asset('storage/' . $otherUser->logo) }}" alt="{{ $otherUser->name }}">
                                @else
                                    <i class="fas fa-users"></i>
                                @endif
                            @else
                                {{-- Untuk chat personal --}}
                                @if($otherUser && $otherUser->avatar)
                                    <img src="{{ asset('storage/' . $otherUser->avatar) }}" alt="{{ $otherUser->name }}">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            @endif
                        </div>
                        @if(!$isCommunity)
                            <div class="online-status"></div>
                        @endif
                    </div>

                    <div class="chat-info">
                        <div class="chat-header-info">
                            <div class="chat-name">
                                {{ $otherUser->name ?? 'Unknown' }}
                                @if($isCommunity)
                                    <span style="font-size: 10px; background: #ECFDF5; color: var(--primary); padding: 2px 6px; border-radius: 4px; margin-left: 4px; font-weight: 600;">Grup</span>
                                @elseif($otherUser && isset($otherUser->role) && $otherUser->role === 'admin')
                                    <i class="fas fa-shield-alt" style="color: var(--warning); font-size: 12px; margin-left: 4px;"></i>
                                @endif
                            </div>
                            <div class="chat-time">
                                {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true, true) : '' }}
                            </div>
                        </div>

                        <div class="chat-preview {{ $unreadCount > 0 ? 'unread' : '' }}">
                            @if($lastMsg)
                                @if($lastMsg->sender_id === auth()->id())
                                    <span style="margin-right: 4px; color: var(--primary);">Anda:</span>
                                @elseif($isCommunity && $lastMsg->sender)
                                    <span style="margin-right: 4px; font-weight: 600;">{{ $lastMsg->sender->name }}:</span>
                                @endif
                                {{ Str::limit($lastMsg->message, 40) }}
                            @else
                                <span style="font-style: italic; opacity: 0.7;">Belum ada pesan</span>
                            @endif
                        </div>
                        
                        @if($conv->field)
                            <div style="font-size: 11px; color: var(--text-tertiary); margin-top: 2px; display: flex; align-items: center; gap: 4px;">
                                <i class="fas fa-map-marker-alt"></i> {{ $conv->field->name }}
                            </div>
                        @endif
                    </div>

                    @if($unreadCount > 0)
                        <div class="unread-badge">{{ $unreadCount }}</div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="far fa-comments"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: var(--text-main);">
                        {{ $search ? 'Percakapan Tidak Ditemukan' : 'Belum Ada Percakapan' }}
                    </h3>
                    <p>
                        {{ $search ? 'Tidak ada percakapan dengan kata kunci "' . $search . '"' : 'Mulai chat dengan pemilik lapangan atau pengguna lain.' }}
                    </p>
                </div>
            @endforelse
        </div>
    @else
        {{-- Discover Users - Remove random key, use wire:key with userType and search --}}
        @livewire('chat.discover-users', ['search' => $search], key('discover-users'))
    @endif

    <!-- FAB Button -->
    <button class="fab-button" wire:click="switchTab('{{ $activeTab === 'chats' ? 'discover' : 'chats' }}')">
        <i class="fas fa-{{ $activeTab === 'chats' ? 'plus' : 'times' }}"></i>
    </button>
</div>