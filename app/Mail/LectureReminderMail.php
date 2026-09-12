<?php

namespace App\Mail;

use App\Models\Lecture;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LectureReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Lecture $lecture,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Lecture Reminder: {$this->lecture->course_code} - {$this->lecture->course_title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lecture-reminder',
        );
    }
}
