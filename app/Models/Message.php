<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'reply_to_id',
        'media_type',
        'media_path',
        'voice_duration',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== Relationships ==========
    
    /**
     * Conversation yang memiliki message ini
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * User yang mengirim message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Message yang di-reply (parent message)
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    /**
     * Messages yang mereply message ini
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    // ========== Accessors & Helpers ==========
    
    /**
     * Check if message has media
     */
    public function hasMedia(): bool
    {
        return !is_null($this->media_type) && !is_null($this->media_path);
    }

    /**
     * Check if message is image
     */
    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    /**
     * Check if message is video
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    /**
     * Check if message is voice note
     */
    public function isVoice(): bool
    {
        return $this->media_type === 'voice';
    }

    /**
     * Check if message is a reply
     */
    public function isReply(): bool
    {
        return !is_null($this->reply_to_id);
    }

    /**
     * Get full media URL
     */
    public function getMediaUrlAttribute(): ?string
    {
        if (!$this->media_path) {
            return null;
        }

        return asset('storage/' . $this->media_path);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    // ========== Scopes ==========
    
    /**
     * Scope unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope messages for a specific user (not sent by them)
     */
    public function scopeNotFrom($query, $userId)
    {
        return $query->where('sender_id', '!=', $userId);
    }

    /**
     * Scope media messages only
     */
    public function scopeWithMedia($query)
    {
        return $query->whereNotNull('media_type');
    }

    /**
     * Scope voice messages only
     */
    public function scopeVoiceOnly($query)
    {
        return $query->where('media_type', 'voice');
    }

    /**
     * Scope image messages only
     */
    public function scopeImageOnly($query)
    {
        return $query->where('media_type', 'image');
    }

    /**
     * Scope video messages only
     */
    public function scopeVideoOnly($query)
    {
        return $query->where('media_type', 'video');
    }
}   