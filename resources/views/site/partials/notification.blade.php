@if(session('success') || $errors->any())

    <div id="siteNotification" class="site-notification-overlay">

        <div class="site-notification">

            <button
                type="button"
                class="site-notification-close"
                onclick="closeSiteNotification()"
                aria-label="Close notification"
            >
                &times;
            </button>

            @if(session('success'))
                <div class="site-notification-icon success">
                    ✓
                </div>

                <h3>Success</h3>

                <p>
                    {{ session('success') }}
                </p>
            @else
                <div class="site-notification-icon error">
                    !
                </div>

                <h3>Please check the following</h3>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

        </div>

    </div>

@endif