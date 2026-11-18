<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MembershipCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Membership $membership,
        public bool $immediate = false
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->immediate
            ? 'Your Gene Decode Membership Has Ended'
            : 'Gene Decode Membership Cancellation Confirmed';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.membership-cancelled',
        );
    }
}