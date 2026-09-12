<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Your Email</title>
    <style>
        body { font-family: 'Instrument Sans', system-ui, sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 32px; text-align: center; }
        .otp-box { background: #f5f3ff; border: 2px dashed #4f46e5; border-radius: 12px; padding: 24px; margin: 24px 0; }
        .otp-code { font-size: 32px; font-weight: 700; color: #4f46e5; letter-spacing: 8px; margin: 0; }
        .footer { padding: 20px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LecAlert</h1>
        </div>
        <div class="content">
            <h2>Verify Your Email</h2>
            <p>Hello {{ $name }},</p>
            <p>Use the following 6-digit code to verify your email address for notification subscriptions:</p>

            <div class="otp-box">
                <p class="otp-code">{{ $otpCode }}</p>
            </div>

            <p style="font-size: 14px; color: #6b7280;">This code expires in 10 minutes. If you didn't request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>LecAlert &mdash; Never Miss a Lecture Again</p>
            <p>Built by Hycon | WhatsApp: +23447177291</p>
        </div>
    </div>
</body>
</html>
