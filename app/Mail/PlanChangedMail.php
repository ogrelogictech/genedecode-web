<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Membership $membership
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Gene Decode Subscription Plan Has Been Updated',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.plan-changed',
        );
    }
}