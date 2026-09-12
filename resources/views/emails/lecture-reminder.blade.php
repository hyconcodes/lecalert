<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lecture Reminder</title>
    <style>
        body { font-family: 'Instrument Sans', system-ui, sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .header p { color: #e0e7ff; margin: 8px 0 0; font-size: 14px; }
        .content { padding: 32px; }
        .lecture-card { background: #f5f3ff; border: 1px solid #e0e7ff; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .lecture-code { font-size: 18px; font-weight: 700; color: #4f46e5; margin: 0; }
        .lecture-title { font-size: 14px; color: #6b7280; margin: 4px 0 16px; }
        .detail-row { display: flex; margin: 8px 0; font-size: 14px; color: #374151; }
        .detail-label { font-weight: 600; min-width: 80px; color: #6b7280; }
        .footer { padding: 20px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 LecAlert Reminder</h1>
            <p>Your lecture is coming up soon!</p>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>This is a reminder that your lecture is starting in <strong>{{ $lecture->reminder_minutes }} minutes</strong>.</p>

            <div class="lecture-card">
                <p class="lecture-code">{{ $lecture->course_code }}</p>
                <p class="lecture-title">{{ $lecture->course_title }}</p>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span>{{ $lecture->lecture_date->format('l, F j, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span>{{ $lecture->start_time_formatted }}{{ $lecture->end_time ? ' - '.$lecture->end_time_formatted : '' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Venue:</span>
                    <span>{{ $lecture->venue }}</span>
                </div>
            </div>

            <p>Don't be late! Good luck with your class.</p>
        </div>
        <div class="footer">
            <p>LecAlert &mdash; Never Miss a Lecture Again</p>
            <p>Built by Hycon | WhatsApp: +23447177291</p>
        </div>
    </div>
</body>
</html>
