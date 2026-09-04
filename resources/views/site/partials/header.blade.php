<div class="ribbon">
    Refined design concept by <b>OgreLogic</b> for GeneDecode
    &nbsp;·&nbsp; page mockups on the unified dark/cosmic system
</div>

<div class="topbar">
    <header class="site">
        <div class="wrap brandrow">

            <a href="{{ url('/') }}">
                <img class="logo" src="{{ asset('site/assets/logo.png') }}" alt="Gene Decode">
            </a>

            <select class="lang" aria-label="Select language">
                <option selected>Select Language</option>
                <option>English</option>
                <option>Español</option>
                <option>Português</option>
                <option>Français</option>
                <option>Deutsch</option>
                <option>Italiano</option>
                <option>Nederlands</option>
            </select>

        </div>
    </header>

    <nav class="main">
        <div class="wrap navbar">

            <button class="navtoggle" aria-label="Toggle menu" aria-expanded="false">
                <span class="ham"></span> Menu
            </button>

            <div class="navlinks">

                <a href="{{ url('/') }}"
                   class="{{ request()->is('/') ? 'on' : '' }}">
                    Home
                </a>

                <a href="{{ url('/about') }}"
                   class="{{ request()->is('about') ? 'on' : '' }}">
                    About Gene Decode
                </a>

                <a href="{{ url('/schedule') }}"
                   class="{{ request()->is('schedule') ? 'on' : '' }}">
                    Schedule
                </a>

                <a href="{{ url('/surface-area') }}"
                   class="{{ request()->is('surface-area') ? 'on' : '' }}">
                    Surface Area
                </a>

                <a href="{{ url('/interviews') }}" class="{{ request()->is('interviews') || (request()->is('watch') && request('from') === 'interviews') ? 'on' : '' }}">Interviews & Videos</a>

                <a href="{{ url('/deep-dives') }}" class="{{ request()->is('deep-dives') || (request()->is('watch') && request('from') === 'deep-dives') ? 'on' : '' }}">Deep Dives</a>

                <a href="{{ url('/community') }}"
                   class="{{ request()->is('community') ? 'on' : '' }}">
                    Community
                </a>

                <a href="{{ url('/donate') }}"
                   class="{{ request()->is('donate') ? 'on' : '' }}">
                    Donate
                </a>

                <a href="{{ url('/faq') }}"
                   class="{{ request()->is('faq') ? 'on' : '' }}">
                    FAQ
                </a>

                <a href="{{ url('/join-us') }}"
                   class="{{ request()->is('join-us') ? 'on' : '' }}">
                    Join Us
                </a>

                <a href="{{ url('/account') }}"
                   class="{{ request()->is('account') ? 'on' : '' }}">
                    Account
                </a>

            </div>
        </div>
    </nav>
</div>