<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Membership $membership
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: Your Gene Decode Membership Payment Failed',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-failed',
        );
    }
}