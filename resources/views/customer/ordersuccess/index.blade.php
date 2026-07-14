<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/cart.css')
</head>

@php
    $payment = $order->payments->last();
@endphp

<body class="bg-[#F8F5F1]">

<div class="cart-wrapper">

    {{-- Header --}}
    <header class="cart-header">
        <h1>Status Pesanan</h1>
    </header>

    {{-- Content --}}
    <main class="cart-content">

        {{-- SUCCESS CARD --}}
        <div class="order-summary text-center">

            {{-- Icon --}}
            <div style="display:flex; justify-content:center; margin-bottom:24px;">

                <div style="
                    width:100px;
                    height:100px;
                    background:#10B981;
                    border-radius:999px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:48px;
                    color:white;
                    font-weight:bold;
                    box-shadow:0 10px 30px rgba(16,185,129,0.25);
                ">
                    ✓
                </div>

            </div>

            {{-- Title --}}
            <h2 style="
                font-size:28px;
                font-weight:700;
                margin-bottom:6px;
                color:#4B2E2B;
            ">
                Pesanan Berhasil
            </h2>

            <p style="
                font-size:14px;
                color:#6B6B6B;
                margin-bottom:10px;
            ">
                Pesanan Anda sedang diproses
            </p>

           

        </div>

        {{-- INFORMASI PESANAN --}}
        <div class="order-summary">

            <h2 style="margin-bottom:18px;">
                Informasi Pesanan
            </h2>

            <div class="summary-row">
                <span>Kode Order</span>
                <span>#{{ $order->order_code }}</span>
            </div>

            <div class="summary-row">
                <span>Nama Pelanggan</span>
                <span>{{ $order->customer_name }}</span>
            </div>

            <div class="summary-row">
                <span>Nomor Meja</span>
                <span>Meja {{ $order->table_id }}</span>
            </div>

            <div class="summary-row">
                <span>Metode Pembayaran</span>

                <span>
                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
                </span>
            </div>

            <div class="summary-row">
                <span>Waktu Pembayaran</span>
                <span>
                    {{ \Carbon\Carbon::parse($order->paid_at ?? now())->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
                </span>
            </div>

        </div>

        {{-- RINCIAN PESANAN --}}
        <div class="order-summary">

            <h2 style="margin-bottom:18px;">
                Rincian Pesanan
            </h2>

            @foreach ($items as $item)

                <div class="summary-row" style="margin-bottom:12px;">

                    <div>
                        <div style="
                            font-weight:600;
                            color:#333;
                            margin-bottom:2px;
                        ">
                            {{ $item->menu->name }}
                        </div>

                        <div style="
                            font-size:12px;
                            color:#777;
                        ">
                            {{ $item->quantity }} ×
                            Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                        </div>
                    </div>

                    <span style="font-weight:600;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>

                </div>

            @endforeach

            <hr class="summary-divider">

            <div class="summary-total-row">
                <span>Total Pembayaran</span>

                <span>
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- ACTION BUTTON --}}
<div class="mt-5 flex flex-col gap-3">

    {{-- PESAN LAGI --}}
    <a href="{{ route('menu.index', $order->table_id) }}"
       class="w-full">

        <button type="button"
            class="checkout-btn"
            style="
                background:#7A4B2F;
                width:100%;
                border-radius:14px;
                height:56px;
                font-weight:700;
                box-shadow:none;
            ">
            Pesan Lagi
        </button>

    </a>

    {{-- DOWNLOAD STRUK --}}
<button type="button" dusk="download-receipt"
    onclick="downloadReceipt()"
    class="checkout-btn"
    style="
        width:100%;
        background:#D6C4B8;
        color:#4B2E2B;
        border-radius:14px;
        height:56px;
        font-weight:700;
        box-shadow:none;
        border:2px solid #B89D8C;
    ">
    Download Struk
</button>

</div>

{{-- HIDDEN RECEIPT --}}
<div id="receipt" style="
    width:320px;
    background:white;
    padding:24px;
    color:black;
    font-family:monospace;
    position:absolute;
    left:-9999px;
    top:0;
">

    {{-- HEADER --}}
    <div style="text-align:center; margin-bottom:16px;">

        <h2 style="
            font-size:22px;
            font-weight:bold;
            margin-bottom:4px;
        ">
            COZY CAFE
        </h2>

        <div style="font-size:12px;">
            Digital Payment Receipt
        </div>

    </div>

    <div style="
        border-top:1px dashed black;
        margin:12px 0;
    "></div>

    {{-- INFO --}}
    <div style="font-size:13px; line-height:1.8;">

        <div>
            <strong>Order</strong> :
            #{{ $order->order_code }}
        </div>

        <div>
            <strong>Customer</strong> :
            {{ $order->customer_name }}
        </div>

        <div>
            <strong>Table</strong> :
            {{ $order->table_id }}
        </div>

        <div>
            <strong>Payment</strong> :
            {{ strtoupper(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
        </div>

        <div>
            <strong>Date</strong> :
            {{ \Carbon\Carbon::parse($order->paid_at ?? now())->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
        </div>

    </div>

    <div style="
        border-top:1px dashed black;
        margin:16px 0;
    "></div>

    {{-- ITEMS --}}
    @foreach ($items as $item)

        <div style="
            margin-bottom:14px;
            font-size:13px;
        ">

            <div style="
                display:flex;
                justify-content:space-between;
                font-weight:bold;
            ">
                <span>{{ $item->menu->name }}</span>

                <span>
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </span>
            </div>

            <div style="
                font-size:12px;
                color:#444;
            ">
                {{ $item->quantity }} ×
                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
            </div>

        </div>

    @endforeach

    <div style="
        border-top:1px dashed black;
        margin:16px 0;
    "></div>

    {{-- TOTAL --}}
    <div style="
        display:flex;
        justify-content:space-between;
        font-size:16px;
        font-weight:bold;
    ">
        <span>TOTAL</span>

        <span>
            Rp {{ number_format($total, 0, ',', '.') }}
        </span>
    </div>

    <div style="
        border-top:1px dashed black;
        margin:16px 0;
    "></div>

    {{-- FOOTER --}}
    <div style="
        text-align:center;
        font-size:12px;
        margin-top:18px;
    ">
        Thank you for your order 
    </div>

</div>





    </main>

</div>


<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    function downloadReceipt() {

        const receipt = document.getElementById('receipt');

        html2canvas(receipt, {
            scale: 3
        }).then(canvas => {

            const link = document.createElement('a');

            link.download = 'receipt-{{ $order->order_code }}.png';

            link.href = canvas.toDataURL('image/png');

            link.click();
        });
    }
</script>


</body>
</html>