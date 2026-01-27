<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('New Property Inquiry') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .info-box h3 {
            margin-top: 0;
            color: #2563eb;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            width: 120px;
        }
        .info-value {
            flex: 1;
        }
        .btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('New Property Inquiry') }}</h1>
        <p>{{ __('A customer is interested in one of your properties') }}</p>
    </div>

    <div class="content">
        <div class="info-box">
            <h3>{{ __('Contact Information') }}</h3>
            <div class="info-row">
                <span class="info-label">{{ __('Name') }}:</span>
                <span class="info-value">{{ $inquiry->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Phone') }}:</span>
                <span class="info-value" dir="ltr">{{ $inquiry->phone }}</span>
            </div>
            @if($inquiry->email)
            <div class="info-row">
                <span class="info-label">{{ __('Email') }}:</span>
                <span class="info-value">{{ $inquiry->email }}</span>
            </div>
            @endif
            @if($inquiry->message)
            <div class="info-row">
                <span class="info-label">{{ __('Message') }}:</span>
                <span class="info-value">{{ $inquiry->message }}</span>
            </div>
            @endif
        </div>

        <div class="info-box">
            <h3>{{ __('Property Details') }}</h3>
            <div class="info-row">
                <span class="info-label">{{ __('Title') }}:</span>
                <span class="info-value">{{ $inquiry->unit->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Type') }}:</span>
                <span class="info-value">
                    @switch($inquiry->unit->type)
                        @case('rental') {{ __('Rental') }} @break
                        @case('sale') {{ __('Sale') }} @break
                        @case('under_construction') {{ __('Under Construction') }} @break
                    @endswitch
                </span>
            </div>
            @if($inquiry->unit->city)
            <div class="info-row">
                <span class="info-label">{{ __('Location') }}:</span>
                <span class="info-value">{{ $inquiry->unit->unitArea?->name }}, {{ $inquiry->unit->city->name }}</span>
            </div>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/admin/inquiries/' . $inquiry->id) }}" class="btn">
                {{ __('View in Dashboard') }}
            </a>
        </div>

        <p style="color: #6b7280; font-size: 14px; margin-top: 30px; text-align: center;">
            {{ __('This is an automated message from') }} {{ site_name() }}
        </p>
    </div>
</body>
</html>
