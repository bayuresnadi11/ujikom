// public/js/chat-box.js - FIXED VERSION

let currentReplyTo = null;
let mediaRecorder = null;
let audioChunks = [];
let recordingInterval = null;
let recordingSeconds = 0;
let selectedMessage = null;
let currentAudio = null;
let selectedFile = null;

// ========== Initialize ==========
document.addEventListener('DOMContentLoaded', function () {
    initializeChat();
});

function initializeChat() {
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const voiceBtn = document.getElementById('voiceBtn');
    const captionInput = document.getElementById('captionInput');

    console.log('Initializing chat...');
    console.log('Voice button found:', voiceBtn ? 'YES' : 'NO');

    // Auto-resize textarea
    if (messageInput) {
        messageInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Send on Enter (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) {
                    Livewire.dispatch('sendMessage');
                }
            }
        });
    }

    // Caption input enter key
    if (captionInput) {
        captionInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendAttachment();
            }
        });
    }

    // Voice recording button
    if (voiceBtn) {
        // Remove old listener to avoid duplicates
        voiceBtn.removeEventListener('click', startRecording);
        voiceBtn.addEventListener('click', startRecording);
        console.log('Voice button listener attached');
    } else {
        console.warn('Voice button (voiceBtn) not found in DOM');
    }

    // Context menu handlers
    setupContextMenu();

    // Scroll to bottom on load
    scrollToBottom();
}

// ========== Auto Scroll ==========
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

// Listen to Livewire events
window.addEventListener('messageSent', () => {
    setTimeout(scrollToBottom, 100);
});

window.addEventListener('scrollToBottom', scrollToBottom);

document.addEventListener('livewire:navigated', () => {
    scrollToBottom();
});

// ========== Context Menu ==========
function setupContextMenu() {
    // Click on message bubble
    document.addEventListener('click', function (e) {
        const bubble = e.target.closest('.msg-bubble');


        // Don't trigger if clicking on media or links
        if (e.target.tagName === 'IMG' || e.target.tagName === 'VIDEO' || e.target.tagName === 'A' || e.target.closest('button')) {
            return;
        }

        if (bubble) {
            e.preventDefault();
            selectedMessage = bubble;
            showContextMenu(e, bubble);
        } else {
            hideContextMenu();
        }
    });

    // Reply option
    const replyOption = document.getElementById('replyOption');
    if (replyOption) {
        replyOption.addEventListener('click', handleReply);
    }

    // Delete option
    const deleteOption = document.getElementById('deleteOption');
    if (deleteOption) {
        deleteOption.addEventListener('click', handleDelete);
    }
}

function showContextMenu(e, bubble) {
    const menu = document.getElementById('contextMenu');
    if (!menu) return;

    const isMine = bubble.dataset.isMine === 'true';

    // Show/hide options
    const replyOption = document.getElementById('replyOption');
    const deleteOption = document.getElementById('deleteOption');

    if (replyOption) replyOption.style.display = 'flex';
    if (deleteOption) deleteOption.style.display = isMine ? 'flex' : 'none';

    // Position menu
    let x = e.clientX;
    let y = e.clientY;

    // Adjust if menu goes off screen
    const menuWidth = 160;
    const menuHeight = 100;

    if (x + menuWidth > window.innerWidth) {
        x = window.innerWidth - menuWidth - 10;
    }

    if (y + menuHeight > window.innerHeight) {
        y = window.innerHeight - menuHeight - 10;
    }

    menu.style.left = x + 'px';
    menu.style.top = y + 'px';
    menu.classList.add('show');
}

function hideContextMenu() {
    const menu = document.getElementById('contextMenu');
    if (menu) {
        menu.classList.remove('show');
    }
}

// ========== Reply Handler ==========
function handleReply() {
    if (!selectedMessage) return;

    const author = selectedMessage.dataset.sender;
    const messageText = selectedMessage.querySelector('.msg-text');
    const text = messageText ? messageText.textContent : 'Media';

    const msgId = selectedMessage.dataset.msgId;

    currentReplyTo = {
        id: msgId,
        author: author,
        text: text
    };

    document.getElementById('replyAuthor').textContent = author;
    document.getElementById('replyText').textContent = text;
    document.getElementById('replyBar').style.display = 'flex';

    // Set reply ID in Livewire
    Livewire.dispatch('setReplyTo', { messageId: msgId });

    const messageInput = document.getElementById('messageInput');
    if (messageInput) messageInput.focus();

    hideContextMenu();
}

function cancelReply() {
    currentReplyTo = null;
    document.getElementById('replyBar').style.display = 'none';
    Livewire.dispatch('cancelReply');
}

// ========== Delete Handler ==========
function handleDelete() {
    if (!selectedMessage) return;

    if (confirm('Hapus pesan ini?')) {
        const msgId = selectedMessage.dataset.msgId;
        Livewire.dispatch('deleteMessage', { messageId: msgId });
    }

    hideContextMenu();
}

// ========== File Upload & Preview ==========
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    const isImage = file.type.startsWith('image/');
    const isVideo = file.type.startsWith('video/');

    if (!isImage && !isVideo) {
        alert('Hanya gambar dan video yang diperbolehkan');
        event.target.value = '';
        return;
    }

    // Check file size (max 10MB for images, 50MB for videos)
    const maxSize = isImage ? 10 * 1024 * 1024 : 50 * 1024 * 1024;
    if (file.size > maxSize) {
        alert(`Ukuran file terlalu besar. Maksimal ${isImage ? '10MB' : '50MB'}`);
        event.target.value = '';
        return;
    }

    selectedFile = file;

    // Show preview
    const previewContainer = document.getElementById('attachmentPreview');
    const content = document.getElementById('attachmentContent');
    const captionInput = document.getElementById('captionInput');

    if (previewContainer && content) {
        const objectUrl = URL.createObjectURL(file);

        if (isImage) {
            content.innerHTML = `<img src="${objectUrl}" alt="Preview">`;
        } else {
            content.innerHTML = `<video src="${objectUrl}" controls></video>`;
        }

        // Copy current message to caption
        const messageInput = document.getElementById('messageInput');
        if (messageInput && captionInput) {
            captionInput.value = messageInput.value;
            // Also update Livewire model for message/caption
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).set('message', messageInput.value);
        }

        previewContainer.style.display = 'flex';
    }
}

function cancelAttachment() {
    selectedFile = null;
    const fileInput = document.getElementById('fileInput');
    if (fileInput) fileInput.value = '';

    document.getElementById('attachmentPreview').style.display = 'none';
    document.getElementById('attachmentContent').innerHTML = '';
}

function sendAttachment() {
    if (!selectedFile) return;

    const component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));

    component.upload('mediaFile', selectedFile, (uploadedFilename) => {
        // Success callback
        component.call('sendMessage');
        cancelAttachment();
    }, () => {
        // Error callback
        alert('Gagal mengupload file');
    });
}

// ========== Media Preview (Viewing) ==========
function showMediaPreview(src, type) {
    const modal = document.getElementById('mediaModal');
    const content = document.getElementById('mediaPreviewContent');

    if (!modal || !content) return;

    if (type === 'image') {
        content.innerHTML = `<img src="${src}" alt="Preview">`;
    } else if (type === 'video') {
        content.innerHTML = `<video src="${src}" controls autoplay></video>`;
    }

    modal.classList.add('show');
}

function closeMediaPreview() {
    const modal = document.getElementById('mediaModal');
    const content = document.getElementById('mediaPreviewContent');

    if (modal) modal.classList.remove('show');
    if (content) content.innerHTML = '';

    // Stop video if playing
    const video = content?.querySelector('video');
    if (video) {
        video.pause();
        video.currentTime = 0;
    }
}

// ========== Voice Recording (FIXED) ==========
async function startRecording() {
    console.log('=== START RECORDING CLICKED ===');

    try {
        // Check browser support
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('❌ Browser tidak mendukung perekaman suara\n\nGunakan Chrome, Firefox, atau Edge terbaru');
            return;
        }

        console.log('Requesting microphone access...');
        const stream = await navigator.mediaDevices.getUserMedia({
            audio: {
                echoCancellation: true,
                noiseSuppression: true
            }
        });
        console.log('✅ Microphone access granted!');

        // Try different codecs - Prioritize most compatible
        let options = {};
        const mimeTypes = [
            'audio/mp4',
            'audio/aac',
            'audio/m4a',
            'audio/webm;codecs=opus',
            'audio/webm',
            'audio/ogg;codecs=opus',
            'audio/wav'
        ];

        for (const mimeType of mimeTypes) {
            if (MediaRecorder.isTypeSupported(mimeType)) {
                options = { mimeType };
                console.log('✅ Using MIME type:', mimeType);
                break;
            }
        }

        if (!options.mimeType) {
            alert('❌ Browser tidak mendukung format audio');
            stream.getTracks().forEach(track => track.stop());
            return;
        }

        mediaRecorder = new MediaRecorder(stream, options);
        audioChunks = [];
        recordingSeconds = 0;

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                audioChunks.push(event.data);
                console.log('Chunk received:', event.data.size, 'bytes');
            }
        };

        mediaRecorder.onerror = (e) => {
            console.error('MediaRecorder error:', e);
            alert('❌ Error saat merekam: ' + e.error);
        };

        mediaRecorder.start(); // Standard start (no slices)
        console.log('✅ Recording started (Simple mode)');

        // UI changes
        document.getElementById('normalInput').style.display = 'none';
        document.getElementById('recordingUI').style.display = 'flex';

        // Start timer
        recordingInterval = setInterval(() => {
            recordingSeconds++;
            const mins = Math.floor(recordingSeconds / 60);
            const secs = recordingSeconds % 60;
            document.getElementById('recordingTime').textContent =
                `${mins}:${secs.toString().padStart(2, '0')}`;

            // Auto-stop at 5 min
            if (recordingSeconds >= 300) {
                sendVoiceNote();
            }
        }, 1000);

    } catch (error) {
        console.error('❌ Recording error:', error);

        if (error.name === 'NotAllowedError') {
            alert('⚠️ Akses mikrofon ditolak!\n\n1. Klik ikon 🔒 di address bar\n2. Ubah Microphone ke Allow\n3. Refresh (CTRL+F5)\n4. Coba lagi');
        } else if (error.name === 'NotFoundError') {
            alert('⚠️ Mikrofon tidak ditemukan!\n\nPastikan mikrofon terpasang');
        } else {
            alert('❌ Error: ' + error.message + '\n\nGunakan HTTPS atau localhost');
        }
    }
}

function cancelRecording() {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
    }

    clearInterval(recordingInterval);
    audioChunks = [];
    recordingSeconds = 0;

    // UI changes
    document.getElementById('recordingUI').style.display = 'none';
    document.getElementById('normalInput').style.display = 'flex';
}

function sendVoiceNote() {
    console.log('=== SEND VOICE NOTE ===');

    if (!mediaRecorder || mediaRecorder.state === 'inactive') {
        console.error('MediaRecorder not active');
        alert('❌ Perekaman belum dimulai');
        return;
    }

    mediaRecorder.stop();
    console.log('MediaRecorder stopped, waiting for onstop...');

    mediaRecorder.onstop = () => {
        console.log('MediaRecorder onstop fired');

        if (audioChunks.length === 0) {
            alert('❌ Tidak ada data audio! Mohon bicara lebih lama.');
            cancelRecording();
            return;
        }

        const duration = document.getElementById('recordingTime').textContent;
        const mimeType = mediaRecorder.mimeType || 'audio/webm';
        const audioBlob = new Blob(audioChunks, { type: mimeType });

        console.log('✅ Blob created:', audioBlob.size, 'bytes,', mimeType);

        if (audioBlob.size < 100) {
            alert('❌ Audio terlalu kecil! Mohon rekam lebih lama.');
            cancelRecording();
            return;
        }

        // Convert to base64
        const reader = new FileReader();
        reader.readAsDataURL(audioBlob);

        reader.onloadend = function () {
            const base64Audio = reader.result;
            console.log('✅ Base64 ready, length:', base64Audio.length);

            try {
                // 1. Test Ping
                console.log('Pinging server...');
                Livewire.dispatch('ping');

                // 2. Dispatch upload
                console.log('Dispatching uploadVoiceNote...');
                Livewire.dispatch('uploadVoiceNote', {
                    audio: base64Audio,
                    duration: duration
                });

                // 3. Direct call fallback (DISABLED to prevent duplicates)
                /*
                const chatBoxEl = document.querySelector('[wire\\:id]');
                if (chatBoxEl) {
                    const wireId = chatBoxEl.getAttribute('wire:id');
                    const component = Livewire.find(wireId);
                    if (component) {
                        component.call('uploadVoiceNote', base64Audio, duration);
                    }
                }
                */

                // Show loading on button
                const sendBtn = document.querySelector('.send-voice-btn');
                if (sendBtn) {
                    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                    sendBtn.disabled = true;
                }

            } catch (err) {
                console.error('❌ Send error:', err);
                alert('❌ Gagal mengirim: ' + err.message);
                const sendBtn = document.querySelector('.send-voice-btn');
                if (sendBtn) {
                    sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim';
                    sendBtn.disabled = false;
                }
            }
        };

        reader.onerror = () => {
            alert('❌ Gagal membaca data audio');
            cancelRecording();
        };

        // Cleanup after processing starts
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
        clearInterval(recordingInterval);
        audioChunks = [];
        recordingSeconds = 0;

        // UI changes
        setTimeout(() => {
            document.getElementById('recordingUI').style.display = 'none';
            document.getElementById('normalInput').style.display = 'flex';
        }, 500);
    };
}

// ========== Voice Playback (EXTREME ROBUST) ==========
function playVoice(btn, audioUrl) {
    const icon = btn.querySelector('i');
    const waveform = btn.nextElementSibling;

    // Ensure absolute URL
    const fullUrl = audioUrl.startsWith('http') ? audioUrl : window.location.origin + audioUrl;

    console.log('=== Voice Playback Diagnostics ===');
    console.log('Target URL:', fullUrl);

    // Toggle Play/Pause on same audio
    if (currentAudio && currentAudio.dataset.url === fullUrl) {
        if (!currentAudio.paused) {
            console.log('Pausing current audio');
            currentAudio.pause();
            icon.className = 'fas fa-play';
            return;
        } else {
            console.log('Resuming current audio');
            currentAudio.play().catch(e => console.error('Resume failed:', e));
            icon.className = 'fas fa-pause';
            return;
        }
    }

    // Stop and reset all
    if (currentAudio) {
        currentAudio.pause();
        currentAudio = null;
    }
    document.querySelectorAll('.voice-play-btn i').forEach(i => i.className = 'fas fa-play');

    icon.className = 'fas fa-spinner fa-spin';

    console.log('Fetching audio as blob...');
    fetch(fullUrl)
        .then(response => {
            console.log('Response Status:', response.status, response.statusText);
            console.log('Content-Type:', response.headers.get('content-type'));
            if (!response.ok) throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
            return response.blob();
        })
        .then(blob => {
            console.log('Blob received size:', blob.size, 'type:', blob.type);

            // If blob is too small, it's likely corrupt/empty
            if (blob.size < 100) {
                throw new Error('File audio terlalu kecil atau kosong (Corrupt)');
            }

            // Create URL from blob (using original blob type if available)
            const blobUrl = URL.createObjectURL(blob);
            console.log('Created Blob URL:', blobUrl);

            const audio = new Audio();
            audio.src = blobUrl;
            audio.dataset.url = fullUrl;
            currentAudio = audio;

            audio.ontimeupdate = () => {
                if (audio.duration && waveform) {
                    const progress = (audio.currentTime / audio.duration) * 100;
                    waveform.style.setProperty('--progress', progress + '%');
                }
            };

            audio.onended = () => {
                icon.className = 'fas fa-play';
                if (waveform) waveform.style.setProperty('--progress', '0%');
                currentAudio = null;
                URL.revokeObjectURL(blobUrl);
            };

            audio.onerror = (e) => {
                const err = audio.error;
                console.error('❌ Audio Object Error:', err ? err.message : 'Unknown');
                alert(`❌ Gagal Memutar: ${err ? err.message : 'Error tidak diketahui'}\n\nFile mungkin corrupt atau browser tidak mendukung format ini.`);
                icon.className = 'fas fa-play';
                URL.revokeObjectURL(blobUrl);
            };

            console.log('Attempting to play...');
            return audio.play();
        })
        .then(() => {
            console.log('▶️ Playback started successfully');
            icon.className = 'fas fa-pause';
        })
        .catch(err => {
            console.error('❌ Playback Pipeline Failed:', err);
            icon.className = 'fas fa-play';
            alert('❌ ERROR PLAYBACK:\n' + err.message);
        });
}


// Utility untuk test kirim tanpa rekam (Bisa dipanggil dari console: sendDebugAudio())
window.sendDebugAudio = function () {
    console.log('DEBUG: Testing voice upload sequence...');
    const dummyAudio = 'data:audio/webm;base64,GkXfo59ChoEBQveBAULygQRC84EIQoKEd2VibUKHgQJLhYEDZuOBAR6bhQZWcm9tZXVzTFNXWC6BA0uNgWun34EDZuOBAX6bhQH9';
    const duration = '0:01';

    Livewire.dispatch('uploadVoiceNote', {
        audio: dummyAudio,
        duration: duration
    });

    const chatBoxEl = document.querySelector('[wire\\:id]');
    if (chatBoxEl) {
        const component = Livewire.find(chatBoxEl.getAttribute('wire:id'));
        if (component) component.call('uploadVoiceNote', dummyAudio, duration);
    }
    alert('Debug audio sent! Check laravel.log');
};

// ========== Livewire Hooks ==========
document.addEventListener('livewire:init', () => {
    Livewire.on('message-sent', () => {
        console.log('✅ MESSAGE SENT event received');

        // Reset input
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
        }

        // Cancel reply if active
        if (currentReplyTo) {
            cancelReply();
        }

        // Scroll to bottom
        setTimeout(scrollToBottom, 100);
    });

    Livewire.on('message-deleted', () => {
        setTimeout(scrollToBottom, 100);
    });

    Livewire.on('showToast', (data) => {
        console.log('📢 Toast event:', data);
        if (data && data[0]) {
            const toast = data[0];
            if (toast.type === 'error') {
                alert('❌ ' + toast.message);
            } else {
                console.log('✅ ' + toast.message);
            }
        }
    });
});

// Re-initialize after Livewire updates (Livewire 3)
document.addEventListener('livewire:navigated', () => {
    console.log('Livewire navigated - re-initializing chat');
    initializeChat();
});

// Also handle Livewire component updates (Livewire 2 compatibility)
window.addEventListener('livewire:load', () => {
    console.log('Livewire loaded - initializing chat');
    initializeChat();
});

window.addEventListener('livewire:update', () => {
    console.log('Livewire updated - re-initializing chat');
    initializeChat();
});

// ========== Close modals on Escape ==========
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeMediaPreview();
        hideContextMenu();
        if (document.getElementById('attachmentPreview').style.display === 'flex') {
            cancelAttachment();
        }
    }
});