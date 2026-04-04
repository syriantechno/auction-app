<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Confirmed – Motor Bazar</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f1f5f9; font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.08); }
        .hero { background: linear-gradient(135deg, #031629 0%, #1d293d 100%); padding: 48px 40px; text-align: center; }
        .hero-logo { font-size: 13px; font-weight: 900; letter-spacing: 0.4em; color: #ff6900; text-transform: uppercase; margin-bottom: 24px; }
        .hero-icon { width: 70px; height: 70px; background: #ff6900; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px; }
        .hero-title { font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; line-height: 1.2; }
        .hero-subtitle { font-size: 14px; color: #94a3b8; margin-top: 10px; font-weight: 500; }
        .body { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 800; color: #031629; margin-bottom: 12px; }
        .message-text { font-size: 15px; line-height: 1.7; color: #475569; }
        .divider { height: 1px; background: #f1f5f9; margin: 28px 0; }
        .summary-box { background: #f8fafc; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; }
        .summary-title { font-size: 11px; font-weight: 900; letter-spacing: 0.2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 16px; }
        .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .summary-row:last-child { border-bottom: none; }
        .summary-label { font-size: 13px; color: #64748b; font-weight: 600; }
        .summary-value { font-size: 13px; color: #1e293b; font-weight: 800; }
        .steps { margin: 28px 0; }
        .step { display: flex; gap: 16px; align-items: flex-start; margin-bottom: 16px; }
        .step-num { width: 32px; height: 32px; background: #ff6900; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 900; flex-shrink: 0; }
        .step-content { flex: 1; }
        .step-title { font-size: 14px; font-weight: 800; color: #031629; }
        .step-desc { font-size: 13px; color: #64748b; margin-top: 2px; }
        .cta-btn { display: block; text-align: center; background: #ff6900; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 900; letter-spacing: 0.1em; text-transform: uppercase; padding: 16px 32px; border-radius: 14px; margin: 28px 0 0; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; }
        .footer-text { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .footer-logo { font-size: 11px; font-weight: 900; color: #ff6900; letter-spacing: 0.3em; text-transform: uppercase; display: block; margin-bottom: 8px; }
        .badge { display: inline-block; padding: 4px 12px; background: #dcfce7; color: #16a34a; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; }

        @php
            $name   = data_get($lead->car_details, 'name', 'Valued Client');
            $make   = data_get($lead->car_details, 'make', '');
            $model  = data_get($lead->car_details, 'model', '');
            $year   = data_get($lead->car_details, 'year', '');
            $date   = data_get($lead->car_details, 'inspection_date', '');
            $time   = data_get($lead->car_details, 'inspection_time', '');
            $type   = data_get($lead->car_details, 'inspection_type', 'branch');
            $phone  = data_get($lead->car_details, 'phone', '');

            // Use custom template from admin settings if set
            $customBody = \App\Models\SystemSetting::get('email_lead_body', '');
            $siteUrl  = config('app.url');
        @endphp
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Hero Header --}}
    <div class="hero">
        <div class="hero-logo">Motor Bazar</div>
        <div class="hero-icon">🚗</div>
        <h1 class="hero-title">Your Request is Confirmed!</h1>
        <p class="hero-subtitle">We've received your details and we're on it.</p>
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">Hello, {{ $name }} 👋</p>

        <p class="message-text" style="margin-top: 12px;">
            @if(!empty($customBody))
                {!! nl2br(e(\App\Models\SystemSetting::get('email_lead_body', ''))) !!}
            @else
                Thank you for reaching out to <strong>Motor Bazar</strong>. We've successfully received your car sell request and our team will be in touch with you shortly.
            @endif
        </p>

        <div class="divider"></div>

        {{-- Summary Box --}}
        <div class="summary-box">
            <div class="summary-title">📋 Your Request Summary</div>
            @if($year || $make || $model)
            <div class="summary-row">
                <span class="summary-label">Vehicle</span>
                <span class="summary-value">{{ $year }} {{ $make }} {{ $model }}</span>
            </div>
            @endif
            @if($date)
            <div class="summary-row">
                <span class="summary-label">Inspection Date</span>
                <span class="summary-value">{{ $date }}</span>
            </div>
            @endif
            @if($time)
            <div class="summary-row">
                <span class="summary-label">Inspection Time</span>
                <span class="summary-value">{{ $time }}</span>
            </div>
            @endif
            <div class="summary-row">
                <span class="summary-label">Inspection Type</span>
                <span class="summary-value">{{ $type === 'home' ? '🏠 Home Service' : '🏢 Branch Visit' }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Reference</span>
                <span class="summary-value">#{{ str_pad($lead->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <div class="divider"></div>

        {{-- What's Next --}}
        <p style="font-size:11px;font-weight:900;letter-spacing:0.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:16px;">What Happens Next</p>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-content">
                    <div class="step-title">Our team reviews your request</div>
                    <div class="step-desc">We'll contact you within 2 business hours to confirm your appointment.</div>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-content">
                    <div class="step-title">Expert inspection</div>
                    <div class="step-desc">A certified Motor Bazar inspector will perform a comprehensive evaluation.</div>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-content">
                    <div class="step-title">Receive your offer</div>
                    <div class="step-desc">Get a competitive market valuation and a final offer within 24 hours.</div>
                </div>
            </div>
        </div>

        <a href="{{ $siteUrl }}" class="cta-btn" style="color:#ffffff;">Visit Motor Bazar →</a>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <span class="footer-logo">Motor Bazar</span>
        <p class="footer-text">
            This email was sent to you because you submitted a sell request on Motor Bazar.<br>
            If you didn't request this, please ignore this email.<br><br>
            © {{ date('Y') }} Motor Bazar. All rights reserved.
        </p>
    </div>
</div>
</body>
</html>
