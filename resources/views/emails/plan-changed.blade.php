@extends('emails.layout')

@section('content')
    <h2 style="margin:0 0 16px; font-size:22px; color:#ffffff;">Subscription Plan Updated</h2>
    <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#cbd5e1;">
        Hi {{ $user->name }}, your Gene Decode membership plan has been successfully switched.
    </p>

    <div style="background:#090f1d; border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:16px; margin:20px 0;">
        <p style="margin:0 0 6px; font-size:13px; color:#94a3b8;">NEW ACTIVE PLAN</p>
        <p style="margin:0; font-size:16px; color:#7cc242; font-weight:700;">
            {{ ucfirst($membership->plan) }} Plan ({{ $membership->plan === 'annual' ? '$77.00 / year' : '$7.00 / month' }})
        </p>
    </div>

    <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#94a3b8;">
        Any prorated credits or adjustments from your previous billing period have been automatically applied to your Stripe billing balance.
    </p>

    <div style="text-align:center; margin-top:24px;">
        <a href="{{ url('/account') }}" style="display:inline-block; background:#7cc242; color:#0a101d; font-weight:700; font-size:14px; padding:12px 28px; border-radius:6px; text-decoration:none;">
            Manage Billing
        </a>
    </div>
@endsection