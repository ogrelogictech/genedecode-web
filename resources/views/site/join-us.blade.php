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
        <div class="checkout">

            <div>

                <div class="card-panel" style="margin-bottom:18px">
                    <h3 style="margin:0 0 14px;font-size:16px">
                        1. Choose your plan
                    </h3>

                    <div class="tiers" style="grid-template-columns:1fr 1fr;margin:0;max-width:none">

                        <label class="tier" style="cursor:pointer;border-width:1px">
                            <div class="body">
                                <h3>Monthly</h3>
                                <div class="price">$7<span>/mo</span></div>
                                <p style="color:var(--muted);font-size:13px;margin:0">
                                    Billed monthly. Cancel anytime.
                                </p>
                            </div>
                        </label>

                        <label class="tier feat" style="cursor:pointer">
                            <span class="badge">SAVE $7</span>

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
                            <label>Full name</label>
                            <input placeholder="Your name">
                        </div>

                        <div class="field">
                            <label>Email</label>
                            <input placeholder="you@email.com">
                        </div>

                    </div>

                    <div class="field">
                        <label>Password</label>
                        <input type="password" placeholder="Create a password">
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

                <button class="btn"
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
    </div>
</section>

@endsection