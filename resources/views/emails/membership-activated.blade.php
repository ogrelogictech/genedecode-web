@extends('emails.layout')

@section('content')
    <h2 style="margin:0 0 16px; font-size:22px; color:#ffffff;">Welcome, {{ $user->name }}!</h2>
    <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#cbd5e1;">
        Thank you for joining <strong>Gene Decode Deep Dives</strong>. Your membership is now active, giving you full access to the complete Deep Dives video library, Q&amp;A Zooms, and live streams.
    </p>

    <div style="background:#090f1d; border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:16px; margin:20px 0;">
        <p style="margin:0 0 8px; font-size:13px; color:#94a3b8;">PLAN DETAILS</p>
        <p style="margin:0 0 4px; font-size:15px; color:#ffffff;">
            <strong>{{ ucfirst($membership->plan) }} Membership</strong>
            ({{ $membership->plan === 'annual' ? '$77.00 / year' : '$7.00 / month' }})
        </p>
        @if($membership->expires_at)
            <p style="margin:0; font-size:13px; color:#94a3b8;">
                Next renewal date: {{ $membership->expires_at->format('F d, Y') }}
            </p>
        @endif
    </div>

    <div style="text-align:center; margin-top:26px;">
        <a href="{{ url('/deep-dives') }}" style="display:inline-block; background:#7cc242; color:#0a101d; font-weight:700; font-size:14px; padding:12px 28px; border-radius:6px; text-decoration:none;">
            Start Watching Deep Dives
        </a>
    </div>
@endsection