<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Habis — Cozy Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f2ed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .card-expired {
            background: #fff;
            border-radius: 24px;
            padding: 40px 32px;
            text-align: center;
            max-width: 360px;
            width: 100%;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
        }
        .icon-wrap {
            width: 72px; height: 72px;
            background: #fff7ed;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        h5 { font-weight: 700; color: #0b1533; margin-bottom: 8px; }
        p  { color: #6b7280; font-size: 14px; line-height: 1.6; }
        .meja-badge {
            background: #5C3A21; color: #fff;
            border-radius: 20px; padding: 5px 14px;
            font-size: 13px; font-weight: 600;
            display: inline-block; margin-bottom: 20px;
        }
        .hint {
            margin-top: 20px;
            background: #f9fafb;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="card-expired">
        <div class="icon-wrap">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 7v5l3 3"/>
            </svg>
        </div>
        <div class="meja-badge">Meja {{ $tableNumber }}</div>
        <h5>Sesi Telah Habis</h5>
        <p>Sesi pemesanan untuk meja ini sudah tidak aktif karena tidak ada aktivitas selama 30 menit.</p>
        <div class="hint">
            Scan ulang QR Code di meja untuk memulai sesi baru.
        </div>
    </div>
</body>
</html>