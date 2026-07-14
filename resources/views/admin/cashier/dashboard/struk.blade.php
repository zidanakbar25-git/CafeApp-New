    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Struk — {{ $order->order_code }}</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background: #f0f0f0;
                font-family: 'Courier New', Courier, monospace;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }

            .struk-wrapper {
                background: #fff;
                width: 320px;
                padding: 28px 24px;
                border-radius: 4px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, .12);
            }

            .cafe-name {
                text-align: center;
                font-size: 18px;
                font-weight: 700;
                letter-spacing: 1px;
                margin-bottom: 2px;
            }

            .cafe-sub {
                text-align: center;
                font-size: 11px;
                color: #666;
                margin-bottom: 14px;
            }

            hr {
                border: none;
                border-top: 1px dashed #bbb;
                margin: 10px 0;
            }

            .info-row {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                margin-bottom: 4px;
            }

            .info-row .label {
                color: #555;
            }

            .items-header {
                display: flex;
                justify-content: space-between;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                color: #888;
                margin-bottom: 6px;
            }

            .item-row {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                margin-bottom: 5px;
                align-items: flex-start;
            }

            .item-name {
                flex: 1;
                padding-right: 8px;
            }

            .item-qty {
                width: 30px;
                text-align: center;
            }

            .item-price {
                width: 80px;
                text-align: right;
                white-space: nowrap;
            }

            .total-row {
                display: flex;
                justify-content: space-between;
                font-size: 14px;
                font-weight: 700;
                margin-top: 4px;
            }

            .status-badge {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .badge-selesai {
                background: #dcfce7;
                color: #16a34a;
            }

            .badge-dibatalkan {
                background: #fee2e2;
                color: #dc2626;
            }

            .badge-menunggu {
                background: #fff3cd;
                color: #d97706;
            }

            .badge-diproses {
                background: #dbeafe;
                color: #2563eb;
            }

            .footer-note {
                text-align: center;
                font-size: 11px;
                color: #888;
                margin-top: 10px;
            }

            .btn-print {
                display: block;
                width: 100%;
                margin-top: 20px;
                padding: 10px;
                background: #0b1533;
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                letter-spacing: .5px;
            }

            .btn-print:hover {
                background: #1e3a5f;
            }

            @media print {
                body {
                    background: #fff;
                    padding: 0;
                }

                .struk-wrapper {
                    box-shadow: none;
                    border-radius: 0;
                }

                .btn-print {
                    display: none;
                }
            }
        </style>
    </head>

    <body>

        <div class="struk-wrapper" id="struk">

            <div class="cafe-name">☕ COZY CAFE</div>
            <div class="cafe-sub">Jl. Contoh No. 1, Bandung</div>
            <div class="cafe-sub">Telp: (022) 1234-5678</div>

            <hr>

            <div class="info-row">
                <span class="label">No. Order</span>
                <span><strong>{{ $order->order_code }}</strong></span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal</span>
                <span>{{ $order->created_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Pelanggan</span>
                <span>{{ $order->customer_name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Meja</span>
                <span>#{{ $order->table_id }}</span>
            </div>
            <div class="info-row">
                <span class="label">Metode Bayar</span>
                <span>{{ strtoupper($paymentMethod) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Kasir</span>
                <span>{{ $kasir }}</span>
            </div>


            <hr>

            <div class="items-header">
                <span>Item</span>
                <span>Qty</span>
                <span>Harga</span>
            </div>

            @foreach($order->orderDetails as $detail)
            <div class="item-row">
                <span class="item-name">{{ $detail->menu->name ?? '-' }}</span>
                <span class="item-qty">{{ $detail->quantity }}</span>
                <span class="item-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach

            <hr>

            <div class="total-row">
                <span>TOTAL</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>

            <hr>

            <div class="footer-note">Terima kasih telah berkunjung!</div>
            <div class="footer-note">Simpan struk ini sebagai bukti pembayaran.</div>

            <button class="btn-print" onclick="window.print()">🖨 Cetak Struk</button>

        </div>

    </body>

    </html>