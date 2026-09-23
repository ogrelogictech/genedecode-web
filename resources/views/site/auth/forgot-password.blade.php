@extends('site.layouts.app')

@section('title', 'Gene Decode — Forgot Password')
@section('page', 'forgot-password')

@section('content')

<section>
    <div class="wrap" style="max-width:520px">

        <div style="text-align:center;margin-bottom:28px">
            <h1 style="font-family:var(--serif);font-size:32px;margin:0 0 8px">
                Forgot your password?
            </h1>

            <p style="color:var(--muted);font-size:14px;margin:0;line-height:1.6">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        <div class="card-panel">

            <form
                method="POST"
                action="{{ url('/forgot-password') }}"
                onsubmit="showResetLoading(this)"
            >
                @csrf

                <div class="field">
                    <label for="email">Email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        oninput="this.value = this.value.replace(/\s/g, '')"
                    >
                </div>

                <button
                    type="submit"
                    class="btn"
                    id="reset-password-btn"
                    style="width:100%; justify-content:center;"
                >
                    <span id="reset-button-text">Send reset link</span>
                    <span id="reset-button-spinner" style="display:none;">
                        <span class="loading-spinner"></span>
                        Sending...
                    </span>
                </button>

            </form>

            <div style="text-align:center;margin-top:22px;padding-top:20px;border-top:1px solid rgba(255,255,255,.08)">

                <p style="color:var(--muted);font-size:14px;margin:0">
                    Remember your password?
                    <a href="{{ url('/login') }}">
                        Sign in
                    </a>
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
        animation: resetSpinner .7s linear infinite;
        vertical-align: -2px;
        margin-right: 7px;
    }

    @keyframes resetSpinner {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    function showResetLoading(form) {
        const button = form.querySelector('#reset-password-btn');
        const buttonText = form.querySelector('#reset-button-text');
        const spinner = form.querySelector('#reset-button-spinner');

        button.disabled = true;
        button.style.cursor = 'default';
        
        buttonText.style.display = 'none';
        spinner.style.display = 'inline-flex';
        spinner.style.alignItems = 'center';
    }

    document.addEventListener('click', function (event) {
        const closeButton = event.target.closest('.site-notification-close');

        if (closeButton) {
            window.location.href = '{{ url('/') }}';
        }
    });
</script>

@endsection

