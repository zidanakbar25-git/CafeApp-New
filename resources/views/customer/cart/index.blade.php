<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#5C3D2E">
    <title>Keranjang Pesanan — Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/cart.css')
</head>
<body>

<div class="cart-wrapper">

    {{-- ── Header ── --}}
    <header class="cart-header">
        <a href="{{ route('menu.index', 1) }}" class="back-btn" aria-label="Kembali">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <h1>Keranjang</h1>
    </header>

    {{-- ── Scrollable Content ── --}}
    <main class="cart-content">

        {{-- Empty State --}}
        @if ($order->orderDetails->isEmpty())
            <div class="cart-empty" id="empty-state">
                <div class="empty-icon">🛒</div>
                <h3>Keranjang Kosong</h3>
                <p>Belum ada item yang ditambahkan.<br>Yuk, pesan sesuatu!</p>
            </div>
        @endif

        {{-- Item List --}}
        <div id="cart-items">
        @foreach ($order->orderDetails as $detail)
            <div class="cart-item-card" dusk="cart-item" id="item-{{ $detail->detail_id }}">

                @if ($detail->menu && $detail->menu->image_url)
                    <img class="cart-item-img"
                         src="{{ asset('storage/' . $detail->menu->image_url) }}"
                         alt="{{ $detail->menu->name }}" loading="lazy">
                @else
                    <div class="cart-item-img-placeholder">☕</div>
                @endif

                <div class="cart-item-info">
                    <div class="cart-item-name">{{ $detail->menu->name ?? '—' }}</div>
                    <div class="cart-item-price">
                        Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                    </div>
                    <div class="cart-item-subtotal">
                        Subtotal:
                        <span id="subtotal-{{ $detail->detail_id }}">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="cart-item-right">

                    {{-- Hapus --}}
                    <button class="delete-btn" dusk="delete-btn"
                            data-detail="{{ $detail->detail_id }}"
                            data-order="{{ $order->order_id }}"
                            onclick="deleteItem(this)"
                            aria-label="Hapus item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6M14 11v6"/>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                    </button>

                    {{-- Qty Controls --}}
                    <div class="qty-controls">
                        <button class="qty-btn minus" dusk="qty-minus"
                                data-detail="{{ $detail->detail_id }}"
                                data-order="{{ $order->order_id }}"
                                data-action="decrement"
                                onclick="updateQty(this)"
                                {{ $detail->quantity <= 1 ? 'disabled' : '' }}>−</button>

                        <span class="qty-value" dusk="qty-value" id="qty-{{ $detail->detail_id }}">
                            {{ $detail->quantity }}
                        </span>

                        <button class="qty-btn plus" dusk="qty-plus"
                                data-detail="{{ $detail->detail_id }}"
                                data-order="{{ $order->order_id }}"
                                data-action="increment"
                                onclick="updateQty(this)">+</button>
                    </div>

                </div>
            </div>
        @endforeach
        </div>

        {{-- Order Summary --}}
        <div class="order-summary" id="order-summary"
             style="{{ $order->orderDetails->isEmpty() ? 'display:none' : '' }}">
            <h2>Ringkasan Pesanan</h2>
            <div id="summary-rows">
                @foreach ($order->orderDetails as $detail)
                    <div class="summary-row" id="summary-row-{{ $detail->detail_id }}">
                        <span class="label">
                            {{ $detail->menu->name ?? '—' }}
                            ×<span id="summary-qty-{{ $detail->detail_id }}">{{ $detail->quantity }}</span>
                        </span>
                        <span class="value" id="summary-sub-{{ $detail->detail_id }}">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
            <hr class="summary-divider">
            <div class="summary-total-row">
                <span class="label">Total</span>
                <span class="value" id="grand-total" dusk="grand-total">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

    </main>

    {{-- ── Sticky Checkout Bar ── --}}
    <div class="checkout-bar">
        <form action="{{ route('cart.checkout', $order->order_id) }}" method="POST">
            @csrf
            <button type="submit" class="checkout-btn"
                    id="checkout-btn" dusk="checkout-btn"
                    {{ $order->orderDetails->isEmpty() ? 'disabled' : '' }}>
                Checkout 
                @if (!$order->orderDetails->isEmpty())
                    &nbsp;·&nbsp;<span id="checkout-total">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                @endif
            </button>
        </form>
    </div>

    {{-- ── Custom Delete Modal ── --}}
    <div id="delete-overlay" style="display:none; position:fixed; inset:0; z-index:999;
         background:rgba(30,20,10,0.45); align-items:center; justify-content:center;">

        <div id="delete-modal" style="
            background: #FAF8F5;
            border-radius: 20px;
            width: 300px;
            padding: 28px 24px 20px;
            text-align: center;
            transform: scale(0.92);
            opacity: 0;
            transition: transform 0.22s cubic-bezier(.34,1.56,.64,1), opacity 0.18s ease;
        ">
            <div style="
                width: 52px; height: 52px; border-radius: 50%;
                background: #FAECE7;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 16px;
            ">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#D85A30" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </div>

            <p style="font-size:16px; font-weight:500; color:#2C2C2A; margin:0 0 6px;">
                Hapus item ini?
            </p>
            <p id="modal-item-name" style="font-size:13px; color:#5F5E5A; margin:0 0 24px; line-height:1.5;">
                Item akan dihapus dari keranjang.
            </p>

            <div style="display:flex; gap:10px;">
                <button onclick="closeDeleteModal()" style="
                    flex:1; padding:12px; border-radius:12px;
                    border: 1.5px solid #D3D1C7;
                    background: transparent; color:#2C2C2A;
                    font-size:14px; font-weight:500; cursor:pointer;
                ">Batal</button>

                <button onclick="confirmDelete()" dusk="confirm-delete"
                    style="
                    flex:1; padding:12px; border-radius:12px;
                    border: none;
                    background: #D85A30; color:#fff;
                    font-size:14px; font-weight:500; cursor:pointer;
                ">Hapus</button>
            </div>
        </div>
    </div>

</div>{{-- /.cart-wrapper --}}

<script>
const CSRF = "{{ csrf_token() }}";

function fmt(num) {
    return 'Rp ' + parseInt(num).toLocaleString('id-ID');
}

function setTotal(total) {
    document.getElementById('grand-total').innerText = fmt(total);
    const ct = document.getElementById('checkout-total');
    if (ct) ct.innerText = fmt(total);
}

function checkEmpty() {
    const items   = document.querySelectorAll('.cart-item-card');
    const empty   = document.getElementById('empty-state');
    const summary = document.getElementById('order-summary');
    const btn     = document.getElementById('checkout-btn');

    if (items.length === 0) {
        if (!empty) {
            const div = document.createElement('div');
            div.id        = 'empty-state';
            div.className = 'cart-empty';
            div.innerHTML = '<div class="empty-icon">🛒</div><h3>Keranjang Kosong</h3><p>Belum ada item yang ditambahkan.<br>Yuk, pesan sesuatu!</p>';
            document.getElementById('cart-items').before(div);
        }
        if (summary) summary.style.display = 'none';
        if (btn) btn.disabled = true;
    } else {
        if (empty) empty.remove();
        if (summary) summary.style.display = '';
        if (btn) btn.disabled = false;
    }
}

// ── Update Qty (AJAX) ────────────────────────────────────────────────────────

async function updateQty(btn) {
    const detailId = btn.dataset.detail;
    const orderId  = btn.dataset.order;
    const action   = btn.dataset.action;

    btn.disabled = true;

    try {
        const res  = await fetch('/cart/update-qty-ajax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ detail_id: detailId, order_id: orderId, action }),
        });
        const data = await res.json();

        document.getElementById(`qty-${detailId}`).innerText         = data.quantity;
        document.getElementById(`summary-qty-${detailId}`).innerText  = data.quantity;
        document.getElementById(`subtotal-${detailId}`).innerText     = fmt(data.subtotal);
        document.getElementById(`summary-sub-${detailId}`).innerText  = fmt(data.subtotal);

        setTotal(data.total);

        const minusBtn = document.querySelector(`.qty-btn.minus[data-detail="${detailId}"]`);
        if (minusBtn) minusBtn.disabled = data.quantity <= 1;

    } catch (e) {
        console.error('Gagal update qty:', e);
    } finally {
        if (action === 'increment') btn.disabled = false;
    }
}

// ── Delete Modal ─────────────────────────────────────────────────────────────

let pendingDelete = null;

function deleteItem(btn) {
    const detailId = btn.dataset.detail;
    const orderId  = btn.dataset.order;
    const card     = document.getElementById(`item-${detailId}`);
    const itemName = card?.querySelector('.cart-item-name')?.innerText ?? 'item ini';

    pendingDelete = { detailId, orderId };

    document.getElementById('modal-item-name').innerText =
        `"${itemName}" akan dihapus dari keranjang.`;

    const overlay = document.getElementById('delete-overlay');
    overlay.style.display = 'flex';

    requestAnimationFrame(() => {
        const modal = document.getElementById('delete-modal');
        modal.style.transform = 'scale(1)';
        modal.style.opacity   = '1';
    });
}

function closeDeleteModal() {
    const modal   = document.getElementById('delete-modal');
    const overlay = document.getElementById('delete-overlay');

    modal.style.transform = 'scale(0.92)';
    modal.style.opacity   = '0';

    setTimeout(() => {
        overlay.style.display = 'none';
        pendingDelete = null;
    }, 180);
}

async function confirmDelete() {
    if (!pendingDelete) return;

    const { detailId, orderId } = pendingDelete;
    closeDeleteModal();

    try {
        const res  = await fetch('/cart/delete-ajax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ detail_id: detailId, order_id: orderId }),
        });
        const data = await res.json();

        document.getElementById(`item-${detailId}`)?.remove();
        document.getElementById(`summary-row-${detailId}`)?.remove();

        setTotal(data.total);
        checkEmpty();

    } catch (e) {
        console.error('Gagal hapus item:', e);
    }
}

// Tap di luar modal → tutup
document.getElementById('delete-overlay').addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});

// Ripple effect pada qty buttons
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        this.style.transform = 'scale(0.88)';
        setTimeout(() => this.style.transform = '', 150);
    });
});
</script>

</body>
</html>