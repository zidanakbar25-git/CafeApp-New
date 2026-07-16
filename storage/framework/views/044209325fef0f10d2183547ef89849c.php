<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRIS Payment</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/cart.css'); ?>
</head>
<body>

<div class="cart-wrapper">

    
    <header class="cart-header">
        <a href="<?php echo e(route('payment.index', $order->order_id)); ?>" class="back-btn">
            ←
        </a>
        <h1>Pembayaran QRIS</h1>
    </header>

    
    <main class="cart-content">

        

        
        <div class="order-summary text-center">

            <h2 style="margin-bottom:20px;">Scan QRIS</h2>

            <div style="display:flex; justify-content:center;">
                <img src="<?php echo e(asset('images/Qris/Qris.jpeg')); ?>"
                     alt="QRIS"
                     style="width:250px; border-radius:16px;">
            </div>

            <p style="margin-top:16px; font-size:14px; color:#5F5E5A;">
                Scan menggunakan DANA, OVO, GoPay,
                Mobile Banking, atau E-Wallet lainnya
            </p>

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

        <form action="<?php echo e(route('payment.finalize', $order->order_id)); ?>" method="POST">

    <?php echo csrf_field(); ?>

    <input type="hidden" name="payment_method" value="qris">

    <button type="submit" class="checkout-btn" dusk="pay-button">
        Saya Sudah Bayar
    </button>

</form>

    </div>

</div>

</body>
</html><?php /**PATH C:\laragon\www\newCafeApp\resources\views/customer/payment/Qris/index.blade.php ENDPATH**/ ?>