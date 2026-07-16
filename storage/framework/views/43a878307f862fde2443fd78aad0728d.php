<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#5C3D2E">
    <title>Keranjang Pesanan — Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/cart.css'); ?>

    <style>
.cart-item-options {
    display: flex;
    flex-direction: column;
    gap: 2px;
    margin: 4px 0 6px;
    padding-left: 10px;
    border-left: 2px solid #E4D9CB;
}

.option-tag {
    font-size: 11.5px;
    color: #8A7A6C;
    line-height: 1.5;
}

.option-tag strong {
    color: #6B4A34;
    font-weight: 600;
}
</style>

</head>
<body>

<div class="cart-wrapper">

    
    <header class="cart-header">
        <a href="<?php echo e(route('menu.index', 1)); ?>" class="back-btn" aria-label="Kembali">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <h1>Keranjang</h1>
    </header>

    
    <main class="cart-content">

        
        <?php if($order->orderDetails->isEmpty()): ?>
            <div class="cart-empty" id="empty-state">
                <div class="empty-icon">🛒</div>
                <h3>Keranjang Kosong</h3>
                <p>Belum ada item yang ditambahkan.<br>Yuk, pesan sesuatu!</p>
            </div>
        <?php endif; ?>

        
        <div id="cart-items">
        <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="cart-item-card" dusk="cart-item" id="item-<?php echo e($detail->detail_id); ?>">

                <?php if($detail->menu && $detail->menu->image_url): ?>
                    <img class="cart-item-img"
                         src="<?php echo e(asset('storage/' . $detail->menu->image_url)); ?>"
                         alt="<?php echo e($detail->menu->name); ?>" loading="lazy">
                <?php else: ?>
                    <div class="cart-item-img-placeholder">☕</div>
                <?php endif; ?>

                <div class="cart-item-info">
                    <div class="cart-item-name"><?php echo e($detail->menu->name ?? '—'); ?></div>

                    <?php if($detail->options->isNotEmpty()): ?>
                    <div class="cart-item-options">
                        <?php $__currentLoopData = $detail->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="option-tag">
                                <strong><?php echo e($opt->group_name); ?>:</strong> <?php echo e($opt->option_name ?? $opt->text_value); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>

                    <div class="cart-item-price">
                        Rp <?php echo e(number_format($detail->unit_price, 0, ',', '.')); ?>

                    </div>
                    <div class="cart-item-subtotal">
                        Subtotal:
                        <span id="subtotal-<?php echo e($detail->detail_id); ?>">
                            Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                        </span>
                    </div>
                </div>

                <div class="cart-item-right">

                    
                    <button class="delete-btn" dusk="delete-btn"
                            data-detail="<?php echo e($detail->detail_id); ?>"
                            data-order="<?php echo e($order->order_id); ?>"
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

                    
                    <div class="qty-controls">
                        <button class="qty-btn minus" dusk="qty-minus"
                                data-detail="<?php echo e($detail->detail_id); ?>"
                                data-order="<?php echo e($order->order_id); ?>"
                                data-action="decrement"
                                onclick="updateQty(this)"
                                <?php echo e($detail->quantity <= 1 ? 'disabled' : ''); ?>>−</button>

                        <span class="qty-value" dusk="qty-value" id="qty-<?php echo e($detail->detail_id); ?>">
                            <?php echo e($detail->quantity); ?>

                        </span>

                        <button class="qty-btn plus" dusk="qty-plus"
                                data-detail="<?php echo e($detail->detail_id); ?>"
                                data-order="<?php echo e($order->order_id); ?>"
                                data-action="increment"
                                onclick="updateQty(this)">+</button>
                    </div>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="order-summary" id="order-summary"
             style="<?php echo e($order->orderDetails->isEmpty() ? 'display:none' : ''); ?>">
            <h2>Ringkasan Pesanan</h2>
            <div id="summary-rows">
                <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="summary-row" id="summary-row-<?php echo e($detail->detail_id); ?>">
                        <span class="label">
                            <?php echo e($detail->menu->name ?? '—'); ?>

                            ×<span id="summary-qty-<?php echo e($detail->detail_id); ?>"><?php echo e($detail->quantity); ?></span>
                        </span>
                        <span class="value" id="summary-sub-<?php echo e($detail->detail_id); ?>">
                            Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <hr class="summary-divider">
            <div class="summary-total-row">
                <span class="label">Total</span>
                <span class="value" id="grand-total" dusk="grand-total">
                    Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                </span>
            </div>
        </div>

    </main>

    
    <div class="checkout-bar">
        <form action="<?php echo e(route('cart.checkout', $order->order_id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="checkout-btn"
                    id="checkout-btn" dusk="checkout-btn"
                    <?php echo e($order->orderDetails->isEmpty() ? 'disabled' : ''); ?>>
                Checkout 
                <?php if(!$order->orderDetails->isEmpty()): ?>
                    &nbsp;·&nbsp;<span id="checkout-total">
                        Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                    </span>
                <?php endif; ?>
            </button>
        </form>
    </div>

    
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

</div>

<script>
const CSRF = "<?php echo e(csrf_token()); ?>";

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
</html><?php /**PATH C:\laragon\www\newCafeApp\resources\views/customer/cart/index.blade.php ENDPATH**/ ?>