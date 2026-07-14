<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>QR Code - Meja {{ $table->table_number }}</title>
    <style>
    body {
        font-family: sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .card {
        text-align: center;
        border: 2px solid #000;
        padding: 30px 40px;
        border-radius: 12px;
    }

    .card h2 {
        font-size: 2rem;
        margin-bottom: 4px;
    }

    .card p {
        color: #555;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    @media print {
        body {
            height: auto;
        }
    }
    </style>
</head>

<body>
    <div class="card">
        <h2>Meja {{ $table->table_number }}</h2>
        <p>Scan untuk melihat menu</p>
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($table->qr_url) !!}
        <p style="margin-top:12px; font-size:0.75rem; color:#999;">{{ $table->qr_url }}</p>
    </div>
    <script>
    window.onload = () => window.print();
    </script>
</body>

</html>