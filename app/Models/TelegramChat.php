<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramChat extends Model
{
    protected $fillable = [
        'telegram_user_id',
        'telegram_username',
        'telegram_phone',
        'first_name',
        'last_name',
        'last_message_at',
        'is_active',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get all messages for this chat
     */
    public function messages()
    {
        return $this->hasMany(TelegramMessage::class);
    }

    /**
     * Get the latest message
     */
    public function latestMessage()
    {
        return $this->hasOne(TelegramMessage::class)->latestOfMany('sent_at');
    }

    /**
     * Get unread messages count
     */
    public function unreadMessages()
    {
        return $this->hasMany(TelegramMessage::class)->where('is_read', false)->where('sender_type', 'user');
    }

    /**
     * Get full name accessor
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Scope to search by username or phone
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('telegram_username', 'like', "%{$term}%")
                ->orWhere('telegram_phone', 'like', "%{$term}%")
                ->orWhere('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%");
        });
    }

    /**
     * Scope to get only active chats
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
