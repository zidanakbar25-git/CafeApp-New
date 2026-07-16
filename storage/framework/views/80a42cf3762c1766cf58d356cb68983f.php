<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#5C3D2E">
    <title>Pembayaran — Cafe</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/cart.css'); ?>

    <style>
        .payment-option {
            display:flex;
            justify-content:space-between;
            align-items:center;
            border:1.5px solid #D3D1C7;
            border-radius:14px;
            padding:12px;
            margin-bottom:10px;
            cursor:pointer;
            background:#FAF8F5;
            transition:all .2s ease;
        }

        .payment-option.active {
            border:2px solid #7A4B2F;
        }

        .payment-left {
            display:flex;
            align-items:center;
            gap:10px;
        }

        .payment-icon {
            width:36px;
            height:36px;
            border-radius:10px;
            background:#EFEAE4;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#7A4B2F;
        }

        .payment-icon.active {
            background:#7A4B2F;
            color:white;
        }

        .payment-title {
            font-size:14px;
            font-weight:500;
        }

        .payment-sub {
            font-size:12px;
            color:#5F5E5A;
        }

        .payment-option input {
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="cart-wrapper">

    
    <header class="cart-header">
        <a href="<?php echo e(route('cart.index', $order->order_id)); ?>" class="back-btn">
            ←
        </a>
        <h1>Pembayaran</h1>
    </header>

    <form method="POST" action="<?php echo e(route('payment.process', $order->order_id)); ?>">
    <?php echo csrf_field(); ?>

    
    <main class="cart-content">

        
        <div class="order-summary">
            <h2>Informasi Pelanggan</h2>

            <label>Nama Lengkap</label>

            <input type="text" dusk="customer-name"
                name="customer_name"
                placeholder="Masukan nama anda"
                style="width:100%; margin-top:6px; padding:10px; border-radius:12px; border:1.5px solid #D3D1C7;" 
                required
                oninvalid="this.setCustomValidity('Nama wajib diisi')"
                oninput="this.setCustomValidity('')">
                
        </div>

        
        <div class="order-summary">
            <h2>Metode Pembayaran</h2>

            
            <label class="payment-option active" dusk="payment-qris">
                <div class="payment-left">
                    <div class="payment-icon active">
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="payment-title">QRIS</div>
                        <div class="payment-sub">Scan QR Code</div>
                    </div>
                </div>
                <input type="radio" name="payment_method"  value="qris" checked >
            </label>


            
            <label class="payment-option" dusk="payment-cc-option">
                <div class="payment-left">
                    <div class="payment-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="payment-title">Kartu Kredit</div>
                        <div class="payment-sub">Visa, Mastercard, Amex</div>
                    </div>
                </div>
                <input type="radio" name="payment_method" value="cc">
            </label>

            
            <label class="payment-option" dusk="payment-cash-option"> 
                <div class="payment-left">
                    <div class="payment-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2">
                            <rect x="2" y="6" width="20" height="12" rx="2"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="payment-title">Tunai</div>
                        <div class="payment-sub">Bayar di kasir</div>
                    </div>
                </div>
                <input type="radio" name="payment_method" value="cash">
            </label>
        </div>

        
        <div class="order-summary">
            <h2>Ringkasan Pesanan</h2>

            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="summary-row">
                    <span><?php echo e($item->menu->name); ?> ×<?php echo e($item->quantity); ?></span>
                    <span>Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <hr class="summary-divider">

            <div class="summary-total-row">
                <span>Total</span>
                <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
            </div>
        </div>

    </main>

    
    <div class="checkout-bar">

        <button type="submit" class="checkout-btn" dusk="pay-button">
            Bayar · Rp <?php echo e(number_format($total, 0, ',', '.')); ?>

        </button>

    </div>

    </form>

</div>


<script>
document.querySelectorAll('.payment-option').forEach(option => {
    option.addEventListener('click', function () {

        document.querySelectorAll('.payment-option').forEach(el => {
            el.classList.remove('active');
            const icon = el.querySelector('.payment-icon');
            if (icon) icon.classList.remove('active');
        });

        this.classList.add('active');

        const icon = this.querySelector('.payment-icon');
        if (icon) icon.classList.add('active');

        this.querySelector('input').checked = true;
    });
});




</script>

</body>
</html><?php /**PATH C:\laragon\www\newCafeApp\resources\views/customer/payment/index.blade.php ENDPATH**/ ?>