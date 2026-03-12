<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class ChatBox extends Component
{
    use WithFileUploads;

    public $conversationId;
    public $receiverId;
    public $message = '';
    public $conversation;
    public $chatMessages = [];
    public $receiver;
    
    // New properties for features
    public $replyToId = null;
    public $mediaFile;
    public $message_id; // Added as per instruction

    protected $rules = [
        'message' => 'nullable|string|max:1000',
        'mediaFile' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200', // 50MB max
    ];

    public function mount($conversationId = null, $receiverId = null)
    {
        if ($conversationId) {
            $this->conversationId = $conversationId;
            $this->loadConversationById($conversationId);
        } elseif ($receiverId) {
            $this->receiverId = $receiverId;
            $this->createOrLoadConversation();
        }
    }

    #[On('conversationSelected')]
    public function loadConversation($conversationId)
    {
        $this->conversationId = $conversationId;
        $this->loadConversationById($conversationId);
    }

    public function loadConversationById($conversationId)
    {
        $this->conversation = Conversation::with(['userOne', 'userTwo', 'field', 'community'])
            ->findOrFail($conversationId);
        
        if (!$this->conversation->isParticipant(Auth::id())) {
            abort(403, 'Unauthorized');
        }

        $this->receiver = $this->conversation->getOtherUser(Auth::id());
        $this->loadMessages();
    }

    public function createOrLoadConversation()
    {
        $this->conversation = Conversation::findOrCreateConversation(
            Auth::id(),
            $this->receiverId
        );

        $this->conversationId = $this->conversation->id;
        $this->receiver = $this->conversation->getOtherUser(Auth::id());
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->conversation) {
            $this->chatMessages = $this->conversation->messages()
                ->with(['sender', 'replyTo.sender'])
                ->get();

            $this->conversation->markAsRead(Auth::id());
            $this->dispatch('scrollToBottom');
            $this->dispatch('refreshChatList');
        }
    }

    #[On('setReplyTo')]
    public function setReplyTo($messageId)
    {
        $this->replyToId = $messageId;
    }

    #[On('cancelReply')]
    public function cancelReply()
    {
        $this->replyToId = null;
    }

    public function sendMessage()
    {
        // Validate
        $this->validate([
            'message' => 'required_without:mediaFile|string|max:1000',
            'mediaFile' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200',
        ]);

        if (!$this->conversation) {
            $this->createOrLoadConversation();
        }

        $mediaType = null;
        $mediaPath = null;

        // Handle file upload
        if ($this->mediaFile) {
            $extension = $this->mediaFile->getClientOriginalExtension();
            $mediaType = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'video';
            
            $mediaPath = $this->mediaFile->store('chat-media', 'public');
        }

        // Create message
        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => Auth::id(),
            'message' => trim($this->message) ?: null,
            'reply_to_id' => $this->replyToId,
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
        ]);

        $this->conversation->update([
            'last_message_at' => now()
        ]);

        // Reset
        $this->message = '';
        $this->mediaFile = null;
        $this->replyToId = null;
        
        $this->loadMessages();
        $this->dispatch('message-sent');
        $this->dispatch('messageSent');

        if ($this->conversation->type !== 'community' && $this->receiver) {
            cache()->forget("user.{$this->receiver->id}.unread_messages");
        }
    }

    public function uploadVoiceNote($audio, $duration = null)
    {
        Log::info('Method uploadVoiceNote CALLED');
        // Handle case where $audio is an array (passed from JS as an object)
        if (is_array($audio)) {
            $duration = $audio['duration'] ?? $duration;
            $audio = $audio['audio'] ?? null;
        }

        Log::info('============ VOICE NOTE UPLOAD START ============');
        Log::info('Received parameters', [
            'audio_start' => substr($audio, 0, 50) . '...',
            'audio_length' => strlen($audio),
            'duration' => $duration,
            'conversation_id' => $this->conversation ? $this->conversation->id : 'NULL',
        ]);
        
        try {
            if (!$this->conversation) {
                Log::info('No conversation found, creating...');
                $this->createOrLoadConversation();
                Log::info('Conversation created', ['id' => $this->conversation->id]);
            }

            // More robust base64 decode
            $audioData = $audio;
            if (str_contains($audio, 'base64,')) {
                $parts = explode('base64,', $audio);
                $audioData = end($parts);
            }
            $audioData = base64_decode($audioData);

            if (!$audioData || strlen($audioData) < 100) {
                Log::error('Voice note data too small or empty', [
                    'size' => strlen($audioData)
                ]);
                throw new \Exception('Invalid voice note data');
            }

            // Detect format from original data URL
            $format = 'webm'; // default
            if (preg_match('#^data:audio/([^;]+);#', $audio, $matches)) {
                $detectedFormat = $matches[1];
                // Map common MIME types to extensions
                $formatMap = [
                    'webm' => 'webm',
                    'ogg' => 'ogg',
                    'opus' => 'opus',
                    'mpeg' => 'mp3',
                    'mp4' => 'm4a',
                    'aac' => 'aac',
                    'wav' => 'wav',
                    'x-wav' => 'wav',
                ];
                $format = $formatMap[$detectedFormat] ?? $detectedFormat;
            }

            // Generate unique filename for initial upload
            $tempFilename = 'voice-notes/' . uniqid() . '_' . time() . '_raw.' . $format;
            
            // Save initial file
            $saved = Storage::disk('public')->put($tempFilename, $audioData);

            // Verify file was saved
            if (!Storage::disk('public')->exists($tempFilename)) {
                throw new \Exception('Failed to save initial voice note');
            }

            // Convert to M4A using FFMPEG
            $finalFilename = $tempFilename;
            $inputPath = Storage::disk('public')->path($tempFilename);
            
            // Target filename (replace _raw.ext with .m4a)
            // If it doesn't have _raw (shouldn't happen), just append .m4a
            $targetName = str_replace("_raw.$format", ".m4a", $tempFilename);
            if ($targetName === $tempFilename) {
                 $targetName = $tempFilename . '.m4a';
            }
            $outputPath = Storage::disk('public')->path($targetName);

            Log::info('Starting FFMPEG conversion', [
                'input' => $inputPath,
                'output' => $outputPath
            ]);

            // Construct command
            // -y: overwrite output
            // -i: input
            // -c:a aac: use AAC codec
            // -b:a 64k: bitrate
            // -movflags +faststart: crucial for web playback (moves moov atom to start)
            // -vn: no video
            $command = "ffmpeg -y -i \"{$inputPath}\" -c:a aac -b:a 64k -movflags +faststart -vn \"{$outputPath}\" 2>&1";
            
            exec($command, $output, $returnCode);

            Log::info('FFMPEG Output:', ['output' => $output, 'code' => $returnCode]);

            if ($returnCode === 0 && file_exists($outputPath) && filesize($outputPath) > 0) {
                Log::info('Conversion successful');
                // Delete original raw file
                Storage::disk('public')->delete($tempFilename);
                $filename = $targetName;
                $format = 'm4a';
            } else {
                Log::error('Conversion failed or output empty', ['return' => $returnCode]);
                // Fallback: keep original file
                $filename = $tempFilename; 
            }

            // Verify final file
            $fileSize = Storage::disk('public')->size($filename);
            Log::info('Voice note final saved', [
                'filename' => $filename,
                'size' => $fileSize,
                'format' => $format
            ]);

            Log::info('Attempting to create Message record...');
            // Create message
            $message = Message::create([
                'conversation_id' => $this->conversation->id,
                'sender_id' => Auth::id(),
                'message' => null, // Voice notes don't have text message
                'media_type' => 'voice',
                'media_path' => $filename,
                'voice_duration' => $duration,
                'reply_to_id' => $this->replyToId,
            ]);

            Log::info('Message record created with ID: ' . $message->id);

            $this->conversation->update([
                'last_message_at' => now()
            ]);

            // Reset
            $this->replyToId = null;
            
            $this->loadMessages();
            $this->dispatch('message-sent');
            $this->dispatch('messageSent');

            Log::info('Voice note message created successfully', [
                'message_id' => $message->id,
                'media_path' => $filename,
                'size' => $fileSize,
            ]);
            
            // Send success notification
            $this->dispatch('showToast', [
                'message' => '✅ Voice note berhasil dikirim!',
                'type' => 'success'
            ]);
            
            Log::info('============ VOICE NOTE UPLOAD SUCCESS ============');

        } catch (\Exception $e) {
            Log::error('============ VOICE NOTE UPLOAD FAILED ============');
            Log::error('Voice note upload failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('showToast', [
                'message' => 'Gagal mengirim voice note: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    #[On('deleteMessage')]
    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->sender_id === Auth::id()) {
            // Delete media file if exists
            if ($message->media_path) {
                Storage::disk('public')->delete($message->media_path);
            }

            $message->delete();
            $this->loadMessages();
            
            $this->dispatch('message-deleted');
            $this->dispatch('showToast', [
                'message' => 'Pesan berhasil dihapus',
                'type' => 'success'
            ]);
        }
    }

    public function getListeners()
    {
        return [
            'uploadVoiceNote' => 'uploadVoiceNote',
            'refresh' => '$refresh',
            'ping' => 'ping',
            "echo-private:conversations.{$this->conversationId},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:conversation.{$this->conversationId},MessageSent" => 'handleNewMessage',
        ];
    }

    public function ping()
    {
        Log::info('PING RECEIVED FROM CLIENT');
        $this->dispatch('showToast', ['message' => 'Pong! Koneksi aman.', 'type' => 'success']);
    }

    public function handleNewMessage($event)
    {
        $this->loadMessages();
    }

    public function render()
    {
        Log::info('ChatBox rendering', [
            'conv_id' => $this->conversation?->id,
            'user_id' => Auth::id()
        ]);
        return view('livewire.chat.chat-box');
    }
}