<!-- resources/views/livewire/chat/chat-box.blade.php -->
<div class="chat-box-wrapper">
    @if($conversation && $receiver)
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="avatar">
                @if($receiver->avatar)
                    <img src="{{ asset('storage/' . $receiver->avatar) }}" alt="{{ $receiver->name }}">
                @else
                    <div class="avatar-text">{{ strtoupper(substr($receiver->name, 0, 2)) }}</div>
                @endif
            </div>
            <div class="chat-info">
                <h3>
                    {{ $receiver->name }}
                    @if($conversation->type !== 'community' && isset($receiver->role) && $receiver->role === 'admin')
                        <i class="fas fa-shield-alt" style="color: #f59e0b;"></i>
                    @endif
                </h3>
                @if($conversation->type === 'community')
                    <p>Grup Komunitas</p>
                @else
                    <p>{{ ucfirst($receiver->role ?? 'Online') }}</p>
                @endif
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container" id="messagesContainer">
            @forelse($chatMessages as $msg)
                <div class="msg-wrapper {{ $msg->sender_id === auth()->id() ? 'sent' : 'received' }}">
                    @if($conversation->type === 'community' && $msg->sender_id !== auth()->id())
                        <div class="sender-name">{{ $msg->sender->name }}</div>
                    @endif
                    
                    <div class="msg-bubble" 
                         data-msg-id="{{ $msg->id }}" 
                         data-sender="{{ $msg->sender->name }}" 
                         data-is-mine="{{ $msg->sender_id === auth()->id() ? 'true' : 'false' }}">
                        
                        <!-- Reply Preview -->
                        @if($msg->reply_to_id && $msg->replyTo)
                            <div class="reply-preview">
                                <div class="reply-author">{{ $msg->replyTo->sender->name }}</div>
                                <div class="reply-text">{{ Str::limit($msg->replyTo->message, 50) }}</div>
                            </div>
                        @endif

                        <!-- Media (Image/Video) -->
                        @if($msg->media_type && $msg->media_path)
                            <div class="msg-media">
                                @if($msg->media_type === 'image')
                                    <img src="{{ asset('storage/' . $msg->media_path) }}" 
                                         alt="Image"
                                         onclick="showMediaPreview('{{ asset('storage/' . $msg->media_path) }}', 'image')">
                                @elseif($msg->media_type === 'video')
                                    <video src="{{ asset('storage/' . $msg->media_path) }}" 
                                           controls
                                           onclick="showMediaPreview('{{ asset('storage/' . $msg->media_path) }}', 'video')"></video>
                                @elseif($msg->media_type === 'voice')
                                    <div class="voice-note">
                                        <button type="button" class="voice-play-btn" onclick="playVoice(this, '/storage/{{ $msg->media_path }}')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <div class="voice-waveform"></div>
                                        <span class="voice-duration">{{ $msg->voice_duration ?? '0:00' }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Text Message -->
                        @if($msg->message)
                            <div class="msg-text">{{ $msg->message }}</div>
                        @endif

                        <!-- Message Meta -->
                        <div class="msg-meta">
                            <span>{{ $msg->created_at->format('H:i') }}</span>
                            @if($msg->sender_id === auth()->id())
                                @if($msg->is_read)
                                    <i class="fas fa-check-double" style="color: #53bdeb;"></i>
                                @else
                                    <i class="fas fa-check"></i>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="far fa-comments"></i>
                    <h3>Belum ada pesan</h3>
                    <p>Mulai percakapan dengan menyapa!</p>
                </div>
            @endforelse
        </div>

        <!-- Reply Bar -->
        <div class="reply-bar" id="replyBar" style="display: none;" wire:ignore>
            <div style="width: 4px; height: 100%; background: var(--primary); border-radius: 2px;"></div>
            <div class="reply-content">
                <div class="reply-author" id="replyAuthor"></div>
                <div class="reply-text" id="replyText"></div>
            </div>
            <button class="reply-close" onclick="cancelReply()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Normal Input -->
        <div class="input-area" id="normalInput" wire:ignore.self>
            <div class="input-actions">
                <label class="input-btn" title="Kirim Gambar/Video">
                    <i class="fas fa-plus"></i>
                    <input type="file" 
                           id="fileInput"
                           accept="image/*,video/*" 
                           onchange="handleFileSelect(event)">
                </label>
            </div>
            <textarea 
                wire:model.defer="message" 
                id="messageInput"
                placeholder="Ketik pesan"
                rows="1"></textarea>
            
            <div class="input-actions">
                 <button class="input-btn" id="voiceBtn" title="Rekam Voice Note">
                    <i class="fas fa-microphone"></i>
                </button>
                <button class="send-btn" wire:click="sendMessage" id="sendBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Recording UI -->
        <div class="recording-ui" id="recordingUI" style="display: none;" wire:ignore>
            <div class="recording-indicator">
                <div class="recording-dot"></div>
                <span>Merekam...</span>
            </div>
            <div class="recording-time" id="recordingTime">0:00</div>
            <div class="recording-actions">
                <button class="cancel-btn" onclick="cancelRecording()">Batal</button>
                <button class="send-voice-btn" onclick="sendVoiceNote()">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </div>
        </div>

        <!-- Attachment Preview Modal (New) -->
        <div class="attachment-preview" id="attachmentPreview" wire:ignore>
            <div class="attachment-header">
                <button class="close-attachment" onclick="cancelAttachment()">
                    <i class="fas fa-times"></i>
                </button>
                <span>Pratinjau Media</span>
                <div style="width: 24px;"></div> <!-- Spacer -->
            </div>
            <div class="attachment-content" id="attachmentContent">
                <!-- Image/Video will be inserted here -->
            </div>
            <div class="attachment-footer">
                 <input type="text" 
                        class="caption-input" 
                        id="captionInput" 
                        placeholder="Tambah keterangan..."
                        wire:model.defer="message">
                <button class="send-attachment-btn" onclick="sendAttachment()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    @else
        <div class="empty-state" style="height: 100vh;">
            <i class="fas fa-inbox"></i>
            <p>Pilih percakapan untuk memulai chat</p>
        </div>
    @endif

    <!-- Context Menu -->
    <div class="context-menu" id="contextMenu">
        <div class="context-menu-item" id="replyOption">
            <i class="fas fa-reply"></i>
            <span>Balas</span>
        </div>
        <div class="context-menu-item" id="deleteOption">
            <i class="fas fa-trash"></i>
            <span>Hapus</span>
        </div>
    </div>

    <!-- Media Preview Modal (For Viewing) -->
    <div class="media-preview-modal" id="mediaModal" wire:ignore>
        <button class="close-preview" onclick="closeMediaPreview()">
            <i class="fas fa-times"></i>
        </button>
        <div class="media-preview-content" id="mediaPreviewContent"></div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/chat-box.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/chat-box.js') }}"></script>
@endpush