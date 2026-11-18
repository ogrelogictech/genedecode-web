@extends('emails.layout')

@section('content')
    <h2 style="margin:0 0 16px; font-size:22px; color:#ffffff;">
        {{ $immediate ? 'Your Membership Has Ended' : 'Cancellation Confirmed' }}
    </h2>

    @if($immediate)
        <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#cbd5e1;">
            Hi {{ $user->name }}, your Gene Decode Deep Dives subscription has ended and your recurring billing is stopped.
        </p>
        <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#cbd5e1;">
            You can rejoin anytime to regain instant access to the full video library.
        </p>
        <div style="text-align:center; margin-top:26px;">
            <a href="{{ url('/join-us') }}" style="display:inline-block; background:#7cc242; color:#0a101d; font-weight:700; font-size:14px; padding:12px 28px; border-radius:6px; text-decoration:none;">
                Rejoin Membership
            </a>
        </div>
    @else
        <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#cbd5e1;">
            Hi {{ $user->name }}, we have processed your cancellation request. Your subscription will not auto-renew.
        </p>
        <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#cbd5e1;">
            You will continue to have full access to all member content until
            <strong>{{ $membership->expires_at ? $membership->expires_at->format('F d, Y') : 'the end of your billing period' }}</strong>.
        </p>
        <div style="text-align:center; margin-top:26px;">
            <a href="{{ url('/account') }}" style="display:inline-block; background:#1e293b; color:#ffffff; border:1px solid rgba(255,255,255,0.2); font-weight:600; font-size:14px; padding:12px 24px; border-radius:6px; text-decoration:none;">
                View Account Details
            </a>
        </div>
    @endif
@endsection