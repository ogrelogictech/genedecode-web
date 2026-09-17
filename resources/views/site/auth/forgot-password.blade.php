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

            <form method="POST" action="{{ url('/forgot-password') }}">
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
                    >
                </div>

                <button
                    type="submit"
                    class="btn"
                    style="width:100%; justify-content: center;"
                >
                    Send reset link
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

@endsection