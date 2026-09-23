@extends('site.layouts.app')

@section('title', 'Gene Decode — Reset Password')
@section('page', 'reset-password')

@section('content')
<section>
    <div class="wrap" style="max-width:520px">

        <div style="text-align:center;margin-bottom:28px">
            <h1 style="font-family:var(--serif);font-size:32px;margin:0 0 8px">
                Reset your password
            </h1>

            <p style="color:var(--muted);font-size:14px;margin:0;line-height:1.6">
                Enter your new password below.
            </p>
        </div>

        <div class="card-panel">

            <form method="POST" action="{{ url('/reset-password') }}" onsubmit="showResetPasswordLoading(this)">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">Email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        autocomplete="email"
                        required readonly
                    >
                </div>

                <div class="field">
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
                            <svg
                                class="eye-icon"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M3 3l18 18"/>
                                <path d="M10.58 10.58a2 2 0 002.83 2.83"/>
                                <path d="M9.88 4.24A9.77 9.77 0 0112 4c5 0 9 8 9 8a16.16 16.16 0 01-2.17 3.19"/>
                                <path d="M6.61 6.61C4.34 8.18 3 12 3 12s4 8 9 8a9.77 9.77 0 01-4.12-.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="field">
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
                            <svg
                                class="eye-icon"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M3 3l18 18"/>
                                <path d="M10.58 10.58a2 2 0 002.83 2.83"/>
                                <path d="M9.88 4.24A9.77 9.77 0 0112 4c5 0 9 8 9 8a16.16 16.16 0 01-2.17 3.19"/>
                                <path d="M6.61 6.61C4.34 8.18 3 12 3 12s4 8 9 8a9.77 9.77 0 01-4.12-.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn"
                    id="reset-password-btn"
                    style="width:100%;justify-content:center;"
                >
                    <span id="reset-password-button-text">Reset password</span>

                    <span id="reset-password-button-spinner" style="display:none;">
                        <span class="loading-spinner"></span>
                        Resetting...
                    </span>
                </button>
            </form>

            <div style="text-align:center;margin-top:22px;padding-top:20px;border-top:1px solid rgba(255,255,255,.08)">
                <p style="color:var(--muted);font-size:14px;margin:0">
                    Remember your password?
                    <a href="{{ url('/login') }}">Sign in</a>
                </p>
            </div>

        </div>
    </div>
</section>

<style>
    .loading-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255,255,255,.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: resetPasswordSpinner .7s linear infinite;
        vertical-align: -2px;
        margin-right: 7px;
    }

    @keyframes resetPasswordSpinner {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    function showResetPasswordLoading(form) {
        const button = form.querySelector('#reset-password-btn');
        const buttonText = form.querySelector('#reset-password-button-text');
        const spinner = form.querySelector('#reset-password-button-spinner');

        button.disabled = true;
        button.style.cursor = 'default';

        buttonText.style.display = 'none';
        spinner.style.display = 'inline-flex';
        spinner.style.alignItems = 'center';
    }
</script>
@endsection