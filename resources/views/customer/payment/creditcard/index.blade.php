<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Card Payment</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/cart.css')

    <style>
        .order-summary {
            margin-top: 20px;
            padding-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="cart-wrapper">

    {{-- Header --}}
    <header class="cart-header">
        <a href="{{ route('payment.index', $order->order_id) }}" class="back-btn">
            ←
        </a>
        <h1>Pembayaran Credit Card</h1>
    </header>

    {{-- Content --}}
    <main class="cart-content">

        {{-- FORM CREDIT CARD --}}
        <div class="order-summary">

            <h2 style="margin-bottom:16px;">Masukkan Data Kartu</h2>

            <label class="text-sm">Nama Pemilik</label>
            <input type="text"
                class="w-full mt-1 mb-3 p-2 border rounded-xl"
                placeholder="Nama sesuai kartu">

            <label class="text-sm">Nomor Kartu</label>
            <input type="text"
                class="w-full mt-1 mb-3 p-2 border rounded-xl"
                placeholder="xxxx xxxx xxxx xxxx">

            <div class="flex gap-2">

                <div class="w-1/2">
                    <label class="text-sm">Expired</label>
                    <input type="text"
                        class="w-full mt-1 p-2 border rounded-xl"
                        placeholder="MM/YY">
                </div>

                <div class="w-1/2">
                    <label class="text-sm">CVV</label>
                    <input type="password"
                        class="w-full mt-1 p-2 border rounded-xl"
                        placeholder="***">
                </div>

            </div>

        </div>

        {{-- Ringkasan --}}
        <div class="order-summary">

            <h2>Ringkasan Pesanan</h2>

            @foreach ($items as $item)
                <div class="summary-row">
                    <span>{{ $item->menu->name }} ×{{ $item->quantity }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach

            <hr class="summary-divider">

            <div class="summary-total-row">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

        </div>

    </main>

    {{-- Footer --}}
    <div class="checkout-bar">

        <form action="{{ route('payment.finalize', $order->order_id) }}" method="POST">

            @csrf

            <input type="hidden" name="payment_method" value="credit_card">

            <button type="submit" class="checkout-btn">
                Bayar · Rp {{ number_format($total, 0, ',', '.') }}
            </button>

        </form>

    </div>

</div>

</body>
</html>