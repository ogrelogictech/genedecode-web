@if(session('success') || $errors->any())

    <div id="siteNotification" class="site-notification-overlay">

        <div class="site-notification" style="background: #e2e8f0 !important; color: #000000 !important; border: 1px solid #cbd5e1; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4); border-radius: 12px; padding: 24px;">

            <button
                type="button"
                class="site-notification-close"
                onclick="closeSiteNotification()"
                aria-label="Close notification"
                style="color: #1e293b; font-weight: bold;"
            >
                &times;
            </button>

            @if(session('success'))
                <div class="site-notification-icon success" style="background: rgba(111, 182, 70, 0.25); color: #1e5622; border: 1.5px solid #489325;">
                    ✓
                </div>

                <h3 style="color: #000000; font-size: 20px; margin-top: 10px; margin-bottom: 8px; font-weight: 700;">Success</h3>

                <p style="color: #111827; font-size: 14.5px; margin: 0; font-weight: 500;">
                    {{ session('success') }}
                </p>
            @else
                <div class="site-notification-icon error" style="background: rgba(229, 62, 62, 0.2); color: #991b1b; border: 1.5px solid #dc2626;">
                    !
                </div>

                <h3 style="color: #000000; font-size: 20px; margin-top: 10px; margin-bottom: 8px; font-weight: 700;">Please check the following</h3>

                <ul style="text-align: left; margin: 10px 0; padding-left: 20px; display: inline-block;">
                    @foreach($errors->all() as $error)
                        <li style="color: #111827; font-weight: 500; font-size: 14px; margin-bottom: 4px;">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div style="margin-top: 20px; display: flex; justify-content: center; width: 100%;">
                <button
                    type="button"
                    class="btn sm"
                    onclick="closeSiteNotification()"
                    style="text-align: center;"
                >
                    OK
                </button>
            </div>

        </div>

    </div>

@endif