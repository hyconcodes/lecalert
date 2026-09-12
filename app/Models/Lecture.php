<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_title',
        'course_code',
        'lecture_date',
        'start_time',
        'end_time',
        'venue',
        'reminder_minutes',
    ];

    protected function casts(): array
    {
        return [
            'lecture_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function reminderLogs(): HasMany
    {
        return $this->hasMany(ReminderLog::class);
    }

    public function getStartTimeFormattedAttribute(): string
    {
        return $this->start_time ? $this->start_time->format('g:i A') : '';
    }

    public function getEndTimeFormattedAttribute(): string
    {
        return $this->end_time ? $this->end_time->format('g:i A') : '';
    }

    public function getStatusAttribute(): string
    {
        $now = now();
        $lectureDateTime = $this->lecture_date->copy()->setTime(
            (int) $this->start_time->format('H'),
            (int) $this->start_time->format('i'),
        );

        if ($this->end_time) {
            $endDateTime = $this->lecture_date->copy()->setTime(
                (int) $this->end_time->format('H'),
                (int) $this->end_time->format('i'),
            );
            if ($now->greaterThan($endDateTime)) {
                return 'completed';
            }
        }

        if ($this->lecture_date->isToday()) {
            if ($now->greaterThanOrEqualTo($lectureDateTime)) {
                return 'ongoing';
            }

            return 'today';
        }

        if ($this->lecture_date->isPast()) {
            return 'completed';
        }

        return 'upcoming';
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming';
    }

    public function isToday(): bool
    {
        return $this->status === 'today' || $this->status === 'ongoing';
    }
}
