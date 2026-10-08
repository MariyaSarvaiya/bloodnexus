<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'blood_request_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'sender_id'
        );
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'receiver_id'
        );
    }

    public function bloodRequest(): BelongsTo
    {
        return $this->belongsTo(
            BloodRequest::class,
            'blood_request_id'
        );
    }
}