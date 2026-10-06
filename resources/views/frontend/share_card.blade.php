<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} — {{ $user->category?->title ?? 'Professional' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #134E4A 0%, #115E59 60%, #0D9488 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .card {
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 32px 80px -12px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #134E4A 0%, #115E59 60%, #0D9488 100%);
            padding: 32px 28px 28px;
            text-align: center;
            position: relative;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 24px;
            background: #ffffff;
            border-radius: 24px 24px 0 0;
        }
        .avatar {
            width: 88px;
            height: 108px;
            object-fit: cover;
            border-radius: 18px;
            border: 3px solid rgba(255,255,255,0.4);
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
            margin: 0 auto 14px;
            display: block;
        }
        .avatar-fallback {
            width: 88px;
            height: 88px;
            border-radius: 18px;
            background: rgba(255,255,255,0.2);
            border: 3px solid rgba(255,255,255,0.4);
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 10px;
        }
        .name {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 4px;
        }
        .subtitle {
            color: rgba(191,219,254,0.9);
            font-size: 0.8rem;
            font-weight: 500;
        }
        .card-body {
            padding: 28px 28px 24px;
        }
        .section-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 14px;
        }
        .service-list {
            list-style: none;
            space-y: 8px;
            margin-bottom: 24px;
        }
        .service-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
        }
        .service-item:last-child { border-bottom: none; }
        .bullet {
            width: 7px;
            height: 7px;
            background: #0F766E;
            border-radius: 999px;
            flex-shrink: 0;
            margin-top: 5px;
        }
        .cta {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #115E59, #0F766E);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 800;
            text-align: center;
            padding: 16px;
            border-radius: 16px;
            text-decoration: none;
            box-shadow: 0 8px 20px -4px rgba(37,99,235,0.45);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -4px rgba(37,99,235,0.5);
        }
        .powered {
            text-align: center;
            font-size: 0.7rem;
            color: #cbd5e1;
            margin-top: 18px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            @if(\App\Support\Avatar::photoUrl($user->profile_photo))
            <img src="{{ \App\Support\Avatar::photoUrl($user->profile_photo) }}" alt="{{ $user->name }}" class="avatar"
                 onerror="this.onerror=null;this.src='{{ \App\Support\Avatar::brandUrl() }}';">
            @else
            <img src="{{ \App\Support\Avatar::brandUrl() }}" alt="{{ $user->name }}" class="avatar">
            @endif

            <div class="badge">
                <i class="fas fa-circle-check" style="font-size:9px"></i>
                Verified {{ $user->category?->title ?? 'Professional' }}
            </div>
            <div class="name">{{ $user->name }}</div>
            @if($user->title || $user->designation)
            <div class="subtitle">{{ $user->title ?? $user->designation }}</div>
            @endif
        </div>

        <div class="card-body">
            @php $activeServices = $user->services->where('is_active', true); @endphp
            @if($activeServices->count())
            <div class="section-label">Services &amp; Pricing List</div>
            <ul class="service-list">
                @foreach($activeServices as $svc)
                <li class="service-item">
                    <span class="bullet"></span>
                    <span>{{ $svc->title }}</span>
                </li>
                @endforeach
            </ul>
            @endif

            <a href="{{ route('frontend.service.show', $user->slug) }}" class="cta">
                👉 View Profile &amp; Services
            </a>

            <div class="powered">Powered by Zonely</div>
        </div>
    </div>
</body>
</html>
