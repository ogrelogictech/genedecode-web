@extends('site.layouts.app')

@section('title', 'Gene Decode — Account')
@section('page', 'account')

@section('content')

<section>
    <div class="wrap" style="max-width:1080px">

        <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
            <div class="avatar" style="width:52px;height:52px;font-size:16px">MS</div>

            <div>
                <h1 style="font-family:var(--serif);font-size:26px;margin:0">
                    Your account
                </h1>
                <p style="color:var(--muted);margin:0;font-size:14px">
                    michelle@genedecode.org · Annual member
                </p>
            </div>
        </div>

        <div class="tabs">
            <a data-target="profile" class="on">Profile</a>
            <a data-target="purchases">Purchases &amp; billing</a>
            <a data-target="email">Email preferences</a>
        </div>

        {{-- Profile --}}
        <div data-panel="profile">

            <div class="card-panel" style="margin-bottom:18px">
                <h3 style="margin:0 0 4px;font-size:16px">
                    My public info
                </h3>

                <p style="color:var(--muted);font-size:13px;margin:0 0 16px">
                    Shown when you participate in the community or comment on videos
                    and live events.
                </p>

                <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
                    <div class="avatar" style="width:56px;height:56px">MS</div>
                    <button class="btn sm ghost">Change photo</button>
                </div>

                <div class="acct-grid">

                    <div class="field">
                        <label>Display name</label>
                        <input placeholder="Your nickname">
                    </div>

                    <div class="field">
                        <label>Location</label>
                        <input placeholder="Select your city">
                    </div>

                </div>

                <div class="field">
                    <label>Bio</label>
                    <textarea rows="3" placeholder="A little about you"></textarea>
                </div>

                <div class="field">
                    <label>Website / socials</label>
                    <input placeholder="http://example.com">
                </div>
            </div>

            <div class="card-panel">

                <h3 style="margin:0 0 16px;font-size:16px">
                    My private info
                </h3>

                <div class="acct-grid">

                    <div class="field">
                        <label>Full name</label>
                        <input value="Michelle Slusser">
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input value="michelle@genedecode.org">
                    </div>

                </div>

                <div class="field" style="max-width:320px">
                    <label>Password</label>
                    <input type="password" value="••••••••••">
                </div>

                <button class="btn sm">Save changes</button>

            </div>
        </div>

        {{-- Purchases & Billing --}}
        <div data-panel="purchases" style="display:none">

            <div class="card-panel" style="margin-bottom:18px">

                <h3 style="margin:0 0 10px;font-size:16px">
                    Membership
                </h3>

                <div class="summary" style="background:transparent;border:none;padding:0">

                    <div class="row">
                        <span>Plan</span>
                        <span>Annual · $77 / year</span>
                    </div>

                    <div class="row">
                        <span>Next renewal</span>
                        <span>18 August 2027</span>
                    </div>

                    <div class="row">
                        <span>Payment method</span>
                        <span>Visa ending 4242</span>
                    </div>

                </div>

                <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap">
                    <button class="btn sm ghost">Change plan</button>
                    <button class="btn sm ghost">Update card</button>
                    <button class="btn sm ghost">Cancel membership</button>
                </div>

            </div>

            <div class="card-panel">

                <h3 style="margin:0 0 12px;font-size:16px">
                    Billing history
                </h3>

                <div class="summary" style="background:transparent;border:none;padding:0">

                    <div class="row">
                        <span>18 Aug 2026 · Annual membership</span>
                        <span>$77.00 · Paid</span>
                    </div>

                    <div class="row">
                        <span>18 Aug 2025 · Annual membership</span>
                        <span>$77.00 · Paid</span>
                    </div>

                </div>

            </div>
        </div>

        {{-- Email Preferences --}}
        <div data-panel="email" style="display:none">

            <div class="card-panel">

                <h3 style="margin:0 0 14px;font-size:16px">
                    Email preferences
                </h3>

                <label style="display:flex;gap:10px;align-items:center;margin-bottom:12px;color:#d7e2d5;font-size:14.5px">
                    <input type="checkbox" checked>
                    New Deep Dive releases and monthly content
                </label>

                <label style="display:flex;gap:10px;align-items:center;margin-bottom:12px;color:#d7e2d5;font-size:14.5px">
                    <input type="checkbox" checked>
                    Live Q&amp;A reminders
                </label>

                <label style="display:flex;gap:10px;align-items:center;margin-bottom:12px;color:#d7e2d5;font-size:14.5px">
                    <input type="checkbox">
                    Community activity and replies
                </label>

                <label style="display:flex;gap:10px;align-items:center;margin-bottom:18px;color:#d7e2d5;font-size:14.5px">
                    <input type="checkbox" checked>
                    Newsletters and announcements
                </label>

                <button class="btn sm">
                    Save preferences
                </button>

            </div>
        </div>

    </div>
</section>

@endsection