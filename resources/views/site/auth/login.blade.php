@extends('site.layouts.app')

@section('title', 'Gene Decode — Login')
@section('page', 'login')

@section('content')

<section>
    <div class="wrap" style="max-width:520px">

        <div style="text-align:center;margin-bottom:28px">
            <h1 style="font-family:var(--serif);font-size:32px;margin:0 0 8px">
                Welcome back
            </h1>

            <p style="color:var(--muted);font-size:14px;margin:0">
                Sign in to access your Gene Decode account.
            </p>
        </div>

        <div class="card-panel">

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="field password-field login-password-field">
                    <label for="password">Password</label>

                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="off"
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

                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin:4px 0 20px;flex-wrap:wrap">

                    <label style="display:flex;align-items:center;gap:8px;color:#d7e2d5;font-size:14px">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >
                        Remember me
                    </label>

                    <a
                        href="{{ url('/forgot-password') }}"
                        style="font-size:14px"
                    >
                        Forgot password?
                    </a>

                </div>

                <button
                    type="submit"
                    class="btn"
                    style="width:100%; justify-content: center;"
                >
                    Sign in
                </button>

            </form>

            <div style="text-align:center;margin-top:22px;padding-top:20px;border-top:1px solid rgba(255,255,255,.08)">

                <p style="color:var(--muted);font-size:14px;margin:0">
                    Don't have an account?
                    <a href="{{ url('/join-us') }}">
                        Join Gene Decode
                    </a>
                </p>

            </div>

        </div>

    </div>
</section>

@endsection