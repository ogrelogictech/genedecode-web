@extends('site.layouts.app')

@section('title', 'Gene Decode — Account')
@section('page', 'account')

@section('content')

<section>

    <div class="wrap" style="max-width:1080px">

        <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
            <div class="avatar" style="width:52px;height:52px;font-size:16px">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>

            <div>
                <h1 style="font-family:var(--serif);font-size:26px;margin:0">
                    Your account
                </h1>

                <p style="color:var(--muted);margin:0;font-size:14px">
                    {{ auth()->user()->email }}
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
                
                <form method="POST" action="{{ route('account.publicprofile.update') }}" enctype="multipart/form-data" >
                    @csrf
                    @method('PUT')

                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
                        <div class="avatar" id="avatarContainer" style="width:56px;height:56px;overflow:hidden;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#1a2332;">
                            @if(auth()->user()->profilepic)
                                <img src="{{ url('/user-avatar/' . basename(auth()->user()->profilepic)) }}" alt="Avatar" id="avatarPreview" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span id="avatarText">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</span>
                            @endif
                        </div>

                        <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none;" onchange="previewImage(this)">
                        
                        <div>
                            <button type="button" class="btn sm ghost" onclick="document.getElementById('avatarInput').click();">
                                Change photo
                            </button>
                            <span style="display:block; color:var(--muted); font-size:12px; margin-top:4px;">
                                JPG, PNG or WEBP. Max size 2MB.
                            </span>
                        </div>
                    </div>

                    <div class="acct-grid">

                        <div class="field">
                            <label for="display_name">Display name</label>
                            <input 
                                type="text" 
                                id="display_name" 
                                name="display_name" 
                                placeholder="Your nickname" required
                                value="{{ old('display_name', auth()->user()->display_name) }}"
                                oninput="this.value = this.value.replace(/^\s+/, '')"
                            >
                            @error('display_name')
                                <span style="color: #ff4d4d; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="location">Location</label>
                            <input 
                                type="text" 
                                id="location" 
                                name="location" 
                                placeholder="Your city" required
                                value="{{ old('location', auth()->user()->location) }}"
                                oninput="this.value = this.value.replace(/^\s+/, '')"
                            >
                            @error('location')
                                <span style="color: #ff4d4d; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="field">
                        <label for="bio">Bio</label>
                        <textarea 
                            id="bio" 
                            name="bio" 
                            rows="3" 
                            placeholder="A little about you" required
                            oninput="this.value = this.value.replace(/^\s+/, '')"
                        >{{ old('bio', auth()->user()->bio) }}</textarea>
                        @error('bio')
                            <span style="color: #ff4d4d; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="website">Website / socials</label>
                        <input 
                            type="text" 
                            id="website" 
                            name="website" 
                            placeholder="http://example.com" 
                            value="{{ old('website', auth()->user()->website) }}"
                            oninput="this.value = this.value.replace(/\s/g, '')"
                        >
                        @error('website')
                            <span style="color: #ff4d4d; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn sm">
                        Save changes
                    </button>

                </form>
            </div>

            <div class="card-panel">

                <h3 style="margin:0 0 16px;font-size:16px">
                    My private info
                </h3>

                <form method="POST" action="{{ route('account.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="acct-grid">

                        <div class="field">
                            <label for="name">Full name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                required
                                oninput="this.value = this.value.replace(/^\s+/, '')" autocomplete="off"
                            >
                        </div>

                        <div class="field">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                required readonly
                                oninput="this.value = this.value.replace(/\s/g, '')"
                            >
                        </div>

                    </div>

                    

                    <button type="submit" class="btn sm">
                        Save changes
                    </button>

                </form>

                <div class="account-password-section">

                    <div class="account-password-header">
                        <h3>Change password</h3>
                        <p>Update your password to keep your account secure.</p>
                    </div>

                    <form method="POST"
                        action="{{ route('account.password.update') }}"
                        id="passwordUpdateForm">

                        @csrf
                        @method('PUT')

                        <div class="field password-field" style="max-width:320px">
                            <label for="current_password">Current password</label>

                            <div class="password-input-wrapper">
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    autocomplete="current-password"
                                    required
                                    oninput="this.value = this.value.replace(/\s/g, '')"
                                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    onclick="togglePassword('current_password', this)"
                                    aria-label="Show password">
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

                        <div class="password-row">

                            <div class="field password-field">
                                <label for="password">New password</label>

                                <div class="password-input-wrapper">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        autocomplete="new-password"
                                        required
                                        oninput="this.value = this.value.replace(/\s/g, '')"
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
                                <label for="password_confirmation">Confirm new password</label>

                                <div class="password-input-wrapper">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        autocomplete="new-password"
                                        required
                                        oninput="this.value = this.value.replace(/\s/g, '')"
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

                        <button type="submit" class="btn sm">
                            Change password
                        </button>

                    </form>

                </div>

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


<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSizeInBytes = 2 * 1024 * 1024; // 2MB

        if (file.size > maxSizeInBytes) {
            showSiteNotification('error', 'Image size is too large. Please select an image under 2MB.');
            input.value = '';
            return;
        }

        var reader = new FileReader();
        
        reader.onload = function(e) {
            var container = document.getElementById('avatarContainer');
            var preview = document.getElementById('avatarPreview');
            var avatarText = document.getElementById('avatarText');

            if (avatarText) {
                avatarText.style.display = 'none';
            }

            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'avatarPreview';
                preview.style.width = '100%';
                preview.style.height = '100%';
                preview.style.objectFit = 'cover';
                container.appendChild(preview);
            }

            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    }
}
</script>
@endsection