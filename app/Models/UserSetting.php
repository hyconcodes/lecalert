<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'default_reminder_minutes',
        'enable_reminders',
        'enable_browser_notifications',
        'enable_sound_notifications',
        'time_format',
    ];

    protected function casts(): array
    {
        return [
            'default_reminder_minutes' => 'integer',
            'enable_reminders' => 'boolean',
            'enable_browser_notifications' => 'boolean',
            'enable_sound_notifications' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
