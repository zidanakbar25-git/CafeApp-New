<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/cart.css')

    <style>
        .nota-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        }

        .divider-dashed {
            border-top: 1px dashed #ccc;
            margin: 12px 0;
        }

        .info-row, .menu-row, .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .menu-left {
            display: flex;
            flex-direction: column;
        }

        .menu-qty-price {
            font-size: 12px;
            color: #777;
        }

        .total-row {
            font-weight: bold;
        }

        .nota-instruction {
            text-align: center;
            margin-bottom: 12px;
        }

        .nota-instruction .icon {
            font-size: 36px;
        }
        .nota-instruction-box {
    background: #EADCD0; /* coklat muda soft */
    border-radius: 16px;
    padding: 14px;
    text-align: center;
    margin-bottom: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

.nota-instruction-box .icon {
    font-size: 32px;
    margin-bottom: 6px;
}

.nota-instruction-box p {
    font-size: 14px;
    font-weight: 700; /* bold */
    color: #000000;   /* coklat lebih gelap biar kontras */
}
    </style>
</head>
<body>

<div class="cart-wrapper">

    {{-- HEADER (DISAMAKAN) --}}
    <header class="cart-header">
        <a href="{{ route('payment.index', $order->order_id) }}" class="back-btn">
            ←
        </a>
        <h1>Nota Pembayaran</h1>
    </header>

    <main class="cart-content">

        {{-- INSTRUKSI --}}
        <div class="nota-instruction-box">
            <div class="icon">🧾</div>
            <p style="font-size:14px; color:#5F5E5A;">
                Tunjukkan nota ini ke kasir untuk melakukan pembayaran
            </p>
        </div>

        {{-- NOTA --}}
        <div class="nota-card">

            {{-- Header Nota --}}
            <div style="text-align:center; margin-bottom:10px;">
                <h2 style="font-weight:bold;">
                    Nota Pembayaran
                </h2>
                <p style="font-size:12px;">
                    {{ now()->format('d M Y, H:i') }}
                </p>
            </div>

            {{-- Info --}}
            <div class="info-row">
                <span>Kode</span>
                <span>#{{ $order->order_code }}</span>
            </div>

            <div class="info-row">
                <span>Pelanggan</span>
                <span ><b>{{ $order->customer_name ?? '-' }}</b></span>
            </div>

            <div class="info-row">
                <span>Meja</span>
                <span>Meja {{ $order->table_id }}</span>
            </div>

            <div class="divider-dashed"></div>

            {{-- Detail --}}
            @foreach ($order->orderDetails as $index => $detail)
            <div class="menu-row">
                <div class="menu-left">
                    <span>{{ $index + 1 }}. {{ $detail->menu->name }}</span>
                    <span class="menu-qty-price">
                        {{ $detail->quantity }}x {{ number_format($detail->unit_price, 0, ',', '.') }}
                    </span>
                </div>
                <span>
                    {{ number_format($detail->subtotal, 0, ',', '.') }}
                </span>
            </div>
            @endforeach

            <div class="divider-dashed"></div>

            {{-- Total --}}
            <div class="total-row">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="divider-dashed"></div>

            

        </div>

       

    </main>

    {{-- Footer --}}
    <div class="checkout-bar">

        <div class="checkout-bar no-print">

    

</div>

    </div>
</div>

</body>
</html>