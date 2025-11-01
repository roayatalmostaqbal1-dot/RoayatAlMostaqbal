<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TelegramMessage;

class TelegramReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_message_id',
        'reply_text',
        'status'
    ];

    public function message()
    {
        return $this->belongsTo(TelegramMessage::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }
}
