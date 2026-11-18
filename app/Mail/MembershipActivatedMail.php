<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MembershipActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Membership $membership
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Gene Decode Deep Dives — Your Membership is Active',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.membership-activated',
        );
    }
}