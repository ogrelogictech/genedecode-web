@extends('site.layouts.app')

@section('title', 'Gene Decode — Join Us')
@section('page', 'join')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Membership</span>
        <h1>Join Gene Decode Deep Dives</h1>
        <p>
            Choose a plan, create your account, and get instant access to the full library,
            the monthly content, live Q&amp;A Zooms, and the community.
        </p>
    </div>
</section>

<section>
    <div class="wrap">
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <div class="checkout">

                <div>

                    <div class="card-panel" style="margin-bottom:18px">
                        <h3 style="margin:0 0 14px;font-size:16px">
                            1. Choose your plan
                        </h3>

                        <div class="tiers" style="grid-template-columns:1fr 1fr;margin:0;max-width:none">

                            <!-- Monthly Plan Card -->
                            <label class="tier" style="cursor:pointer;border-width:1px">
                                <input 
                                    type="radio" 
                                    name="plan" 
                                    value="monthly" 
                                    style="display:none;" 
                                    {{ old('plan', 'monthly') === 'monthly' ? 'checked' : '' }}
                                >
                                <div class="body">
                                    <h3>Monthly</h3>
                                    <div class="price">$7<span>/mo</span></div>
                                    <p style="color:var(--muted);font-size:13px;margin:0">
                                        Billed monthly. Cancel anytime.
                                    </p>
                                </div>
                            </label>

                            <!-- Annual Plan Card (Default / Featured) -->
                            <label class="tier" style="cursor:pointer">
                                <span class="badge">SAVE $7</span>
                                <input 
                                    type="radio" 
                                    name="plan" 
                                    value="annual" 
                                    style="display:none;" 
                                    {{ old('plan', 'annual') === 'annual' ? 'checked' : '' }}
                                >
                                <div class="body">
                                    <h3>Annual</h3>
                                    <div class="price">$77<span>/yr</span></div>
                                    <p style="color:var(--muted);font-size:13px;margin:0">
                                        Two months free vs monthly.
                                    </p>
                                </div>
                            </label>

                        </div>
                    </div>

                    <div class="card-panel" style="margin-bottom:18px">
                        <h3 style="margin:0 0 14px;font-size:16px">
                            2. Create your account
                        </h3>

                        <div class="acct-grid">

                            <div class="field">
                                <label for="name">Full name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    placeholder="Your name"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    required
                                >
                            </div>

                            <div class="field">
                                <label for="email">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="you@email.com"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                >
                            </div>

                        </div>

                        <div class="acct-grid">
                            <div class="field password-field">
                                <label for="password">Password</label>

                                <div class="password-input-wrapper">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Create a password"
                                        autocomplete="new-password"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password', this)"
                                        aria-label="Show password"
                                    >
                                        <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 3l18 18"/>
                                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                            <path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a17.4 17.4 0 0 1-3.1 4.4"/>
                                            <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10.7 10.7 0 0 0 2.1-.2"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="field password-field">
                                <label for="password_confirmation">Confirm Password</label>
                                <div class="password-input-wrapper">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Confirm your password"
                                        autocomplete="new-password"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password_confirmation', this)"
                                        aria-label="Show password"
                                    >
                                        <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 3l18 18"/>
                                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                            <path d="M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a17.4 17.4 0 0 1-3.1 4.4"/>
                                            <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10.7 10.7 0 0 0 2.1-.2"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-panel">
                        <h3 style="margin:0 0 14px;font-size:16px">
                            3. Payment
                        </h3>

                        <div class="field">
                            <label>Card number</label>
                            <input placeholder="1234 1234 1234 1234">
                        </div>

                        <div class="acct-grid">

                            <div class="field">
                                <label>Expiry</label>
                                <input placeholder="MM / YY">
                            </div>

                            <div class="field">
                                <label>CVC</label>
                                <input placeholder="CVC">
                            </div>

                        </div>

                        <p style="color:var(--faint);font-size:12.5px;margin:4px 0 0">
                            Payments are processed securely through Stripe.
                            Your card details never touch our servers.
                        </p>
                    </div>

                </div>

                <div class="summary">

                    <h3 style="margin:0 0 14px;font-size:16px">
                        Order summary
                    </h3>

                    <div class="row">
                        <span>Annual membership</span>
                        <span>$77.00</span>
                    </div>

                    <div class="field" style="margin:10px 0 6px">
                        <label>Coupon code</label>

                        <div style="display:flex;gap:8px">
                            <input placeholder="Enter code">
                            <button class="btn sm ghost">Apply</button>
                        </div>
                    </div>

                    <div class="row">
                        <span>Tax</span>
                        <span>$0.00</span>
                    </div>

                    <div class="row total">
                        <span>Total due today</span>
                        <span>$77.00</span>
                    </div>

                    <button
                        type="submit"
                        class="btn"
                        style="width:100%;justify-content:center;margin-top:16px">
                        Start membership
                    </button>

                    <p style="color:var(--faint);font-size:12px;margin:12px 0 0">
                        By subscribing you agree to the Terms &amp; Conditions and the
                        Subscriber Agreement. Renews automatically; cancel anytime from
                        your account.
                    </p>

                </div>

            </div>
        </form>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.tiers .tier').forEach(function(card) {
        card.addEventListener('click', function() {
            // Remove active highlight style from all cards
            document.querySelectorAll('.tiers .tier').forEach(function(c) {
                c.style.borderColor = 'var(--line)';
            });

            // Highlight the clicked card
            this.style.borderColor = 'var(--brand, #4f46e5)';

            // Select the embedded radio button
            var radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        });
    });
</script>
@endpush