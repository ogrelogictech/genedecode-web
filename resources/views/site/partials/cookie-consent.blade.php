<div id="gd-cookie-banner" style="display: none; position: fixed; bottom: 20px; left: 20px; right: 20px; max-width: 960px; margin: 0 auto; background: #0d1527; border: 1px solid rgba(124, 194, 66, 0.35); border-radius: 10px; padding: 16px 22px; box-shadow: 0 12px 35px rgba(0,0,0,0.65); z-index: 9999;">
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
        <div style="flex: 1 1 520px; font-size: 13.5px; line-height: 1.5; color: #cbd5e1;">
            <strong style="color: #ffffff;">Cookies &amp; Privacy:</strong>
            We use essential cookies to keep you signed in and analytics cookies (via Google Tag Manager) to improve the Gene Decode platform. Read our
            <a href="{{ url('/privacy') }}" style="color: #7cc242; text-decoration: underline;">Privacy Policy</a> and
            <a href="{{ url('/terms') }}" style="color: #7cc242; text-decoration: underline;">Terms &amp; Conditions</a>.
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" id="gd-cookie-essential" style="background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #cbd5e1; padding: 8px 14px; border-radius: 6px; font-size: 13px; cursor: pointer;">
                Essential Only
            </button>
            <button type="button" id="gd-cookie-accept" class="btn sm" style="padding: 8px 18px; cursor: pointer;">
                Accept All
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        var consentKey = 'gd_cookie_consent_v1';
        var banner = document.getElementById('gd-cookie-banner');
        var saved = localStorage.getItem(consentKey);

        if (!saved && banner) {
            banner.style.display = 'block';
        }

        function saveConsent(choice) {
            localStorage.setItem(consentKey, choice);
            if (banner) banner.style.display = 'none';
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'cookie_consent_update',
                'consent_level': choice
            });
        }

        var btnAccept = document.getElementById('gd-cookie-accept');
        var btnEssential = document.getElementById('gd-cookie-essential');
        if (btnAccept) btnAccept.addEventListener('click', function() { saveConsent('all'); });
        if (btnEssential) btnEssential.addEventListener('click', function() { saveConsent('essential'); });
    })();
</script>