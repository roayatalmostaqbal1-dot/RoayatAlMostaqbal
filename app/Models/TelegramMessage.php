<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TelegramReply;
use App\Models\User;

class TelegramMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message_text',
        'is_from_user',
        'status'
    ];

    public function replies()
    {
        return $this->hasMany(TelegramReply::class);
    }

    public function latestReply()
    {
        return $this->hasOne(TelegramReply::class)->latestOfMany();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForChat($query, $chatId)
    {
        return $query->where('chat_id', $chatId);
    }
}
