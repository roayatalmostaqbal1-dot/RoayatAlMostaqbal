<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.security.report.title') }} - {{ __('messages.header.title') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; }
        .header { text-align: center; border-bottom: 3px solid #27e9b5; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 28px; font-weight: bold; color: #051824; margin-bottom: 10px; }
        .report-title { font-size: 24px; color: #162936; margin-bottom: 5px; }
        .report-id { font-size: 12px; color: #666; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 18px; font-weight: bold; color: #051824; border-left: 4px solid #27e9b5; padding-left: 15px; margin-bottom: 15px; }
        .section-content { padding-left: 19px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .info-item { background: #f8f9fa; padding: 15px; border-radius: 8px; }
        .info-label { font-size: 12px; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .info-value { font-size: 14px; font-weight: 600; color: #051824; }
        .feature-list { list-style: none; }
        .feature-list li { padding: 10px 0; border-bottom: 1px solid #eee; display: flex; align-items: flex-start; }
        .feature-list li:last-child { border-bottom: none; }
        .check-icon { color: #27e9b5; margin-right: 10px; font-weight: bold; }
        .compliance-badge { display: inline-block; background: #27e9b5; color: #051824; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 5px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .print-btn { background: #27e9b5; color: #051824; border: none; padding: 12px 30px; border-radius: 25px; font-weight: bold; cursor: pointer; margin-bottom: 20px; }
        .print-btn:hover { background: #1fd9a5; }
        @media print { .print-btn { display: none; } body { background: white; } .container { padding: 20px; } }
    </style>
</head>
<body>
    <div class="container">
        <button class="print-btn" onclick="window.print()">{{ __('messages.security.report.print_button') }}</button>
        
        <div class="header">
            <div class="logo">{{ __('messages.header.title') }}</div>
            <div class="report-title">{{ __('messages.security.report.title') }}</div>
            <div class="report-id">{{ __('messages.security.report.id') }}: {{ $reportId }}</div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">{{ __('messages.security.report.generated_at') }}</div>
                <div class="info-value">{{ $generatedAt }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">{{ __('messages.security.report.valid_until') }}</div>
                <div class="info-value">{{ now()->addYear()->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">{{ __('messages.security.report.zkp.title') }}</h2>
            <div class="section-content">
                <p>{{ __('messages.security.report.zkp.description') }}</p>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">{{ __('messages.security.report.methodology.title') }}</h2>
            <div class="section-content">
                <ul class="feature-list">
                    <li><span class="check-icon">✓</span> {{ __('messages.security.report.methodology.item1') }}</li>
                    <li><span class="check-icon">✓</span> {{ __('messages.security.report.methodology.item2') }}</li>
                    <li><span class="check-icon">✓</span> {{ __('messages.security.report.methodology.item3') }}</li>
                    <li><span class="check-icon">✓</span> {{ __('messages.security.report.methodology.item4') }}</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">{{ __('messages.security.report.compliance.title') }}</h2>
            <div class="section-content">
                <div class="compliance-badge">UAE PDPL</div>
                <div class="compliance-badge">ISO 27001</div>
                <div class="compliance-badge">TLS 1.3</div>
                <div class="compliance-badge">AES-256</div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">{{ __('messages.security.report.verification.title') }}</h2>
            <div class="section-content">
                <p>{{ __('messages.security.report.verification.description') }}</p>
            </div>
        </div>

        <div class="footer">
            <p>{{ __('messages.security.report.footer') }}</p>
            <p>{{ __('messages.header.title') }} - {{ config('app.url') }}</p>
        </div>
    </div>
</body>
</html>

