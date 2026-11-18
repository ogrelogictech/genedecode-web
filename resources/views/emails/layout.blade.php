<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background-color:#070b14; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#e2e8f0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#070b14; padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px; background-color:#0d1527; border:1px solid rgba(124,194,66,0.25); border-radius:10px; overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="padding:24px 32px; border-bottom:1px solid rgba(255,255,255,0.08); text-align:center;">
                            <span style="font-size:20px; font-weight:700; letter-spacing:0.5px; color:#ffffff;">
                                GENE <span style="color:#7cc242;">DECODE</span>
                            </span>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 32px; background-color:#090f1d; border-top:1px solid rgba(255,255,255,0.06); text-align:center; font-size:12px; color:#64748b;">
                            <p style="margin:0 0 6px;">&copy; {{ date('Y') }} Gene Decode. All rights reserved.</p>
                            <p style="margin:0;">
                                <a href="{{ url('/account') }}" style="color:#7cc242; text-decoration:none;">Manage Account</a>
                                &nbsp;&middot;&nbsp;
                                <a href="{{ url('/contact') }}" style="color:#94a3b8; text-decoration:none;">Contact Support</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>