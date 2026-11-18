@extends('emails.layout')

@section('content')
    <h2 style="margin:0 0 16px; font-size:22px; color:#f87171;">Payment Could Not Be Processed</h2>
    <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#cbd5e1;">
        Hi {{ $user->name }}, we were unable to process the renewal payment for your <strong>{{ ucfirst($membership->plan) }} Membership</strong>.
    </p>
    <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#cbd5e1;">
        Stripe will automatically retry the charge over the next few days. To avoid any interruption to your Deep Dives access, please update your payment method in your account billing settings.
    </p>

    <div style="text-align:center; margin-top:26px;">
        <a href="{{ url('/account') }}" style="display:inline-block; background:#7cc242; color:#0a101d; font-weight:700; font-size:14px; padding:12px 28px; border-radius:6px; text-decoration:none;">
            Update Payment Method
        </a>
    </div>
@endsection