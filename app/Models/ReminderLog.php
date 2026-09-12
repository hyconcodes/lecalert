<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecture_id',
        'user_id',
        'scheduled_reminder_time',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_reminder_time' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function lecture(): BelongsTo
    {
        return $this->belongsTo(Lecture::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
