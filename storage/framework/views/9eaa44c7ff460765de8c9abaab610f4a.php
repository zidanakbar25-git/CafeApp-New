<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/cart.css'); ?>

    <style>
    .item-options {
        margin-top: 3px;
        padding-left: 8px;
        border-left: 2px solid #EADCD0;
    }

    .item-option-line {
        display: block;
        font-size: 11px;
        color: #999;
        line-height: 1.5;
    }

    .receipt-option-line {
        font-size: 11px;
        color: #555;
        padding-left: 10px;
    }
</style>


</head>

<?php
    $payment = $order->payments->last();
?>

<body class="bg-[#F8F5F1]">

<div class="cart-wrapper">

    
    <header class="cart-header">
        <h1>Status Pesanan</h1>
    </header>

    
    <main class="cart-content">

        
        <div class="order-summary text-center">

            
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

        
        <div class="order-summary">

            <h2 style="margin-bottom:18px;">
                Informasi Pesanan
            </h2>

            <div class="summary-row">
                <span>Kode Order</span>
                <span>#<?php echo e($order->order_code); ?></span>
            </div>

            <div class="summary-row">
                <span>Nama Pelanggan</span>
                <span><?php echo e($order->customer_name); ?></span>
            </div>

            <div class="summary-row">
                <span>Nomor Meja</span>
                <span>Meja <?php echo e($order->table_id); ?></span>
            </div>

            <div class="summary-row">
                <span>Metode Pembayaran</span>

                <span>
                    <?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method ?? '-'))); ?>

                </span>
            </div>

            <div class="summary-row">
                <span>Waktu Pembayaran</span>
                <span>
                    <?php echo e(\Carbon\Carbon::parse($order->paid_at ?? now())->setTimezone('Asia/Jakarta')->format('d M Y H:i')); ?>

                </span>
            </div>

        </div>

        
        <div class="order-summary">

            <h2 style="margin-bottom:18px;">
                Rincian Pesanan
            </h2>

            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="summary-row" style="margin-bottom:12px; align-items:flex-start;">

                    <div>
                        <div style="
                            font-weight:600;
                            color:#333;
                            margin-bottom:2px;
                        ">
                            <?php echo e($item->menu->name); ?>

                        </div>

                        <div style="
                            font-size:12px;
                            color:#777;
                        ">
                            <?php echo e($item->quantity); ?> ×
                            Rp <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?>

                        </div>

                        <?php if($item->options->isNotEmpty()): ?>
                        <div class="item-options">
                            <?php $__currentLoopData = $item->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="item-option-line">
                                    <?php echo e($opt->group_name); ?>: <?php echo e($opt->option_name ?? $opt->text_value); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <span style="font-weight:600;">
                        Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                    </span>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <hr class="summary-divider">

            <div class="summary-total-row">
                <span>Total Pembayaran</span>

                <span>
                    Rp <?php echo e(number_format($total, 0, ',', '.')); ?>

                </span>
            </div>

        </div>

        
<div class="mt-5 flex flex-col gap-3">

    
    <a href="<?php echo e(route('menu.index', $order->table_id)); ?>"
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

    
    <div style="font-size:13px; line-height:1.8;">

        <div>
            <strong>Order</strong> :
            #<?php echo e($order->order_code); ?>

        </div>

        <div>
            <strong>Customer</strong> :
            <?php echo e($order->customer_name); ?>

        </div>

        <div>
            <strong>Table</strong> :
            <?php echo e($order->table_id); ?>

        </div>

        <div>
            <strong>Payment</strong> :
            <?php echo e(strtoupper(str_replace('_', ' ', $payment->payment_method ?? '-'))); ?>

        </div>

        <div>
            <strong>Date</strong> :
            <?php echo e(\Carbon\Carbon::parse($order->paid_at ?? now())->setTimezone('Asia/Jakarta')->format('d M Y H:i')); ?>

        </div>

    </div>

    <div style="
        border-top:1px dashed black;
        margin:16px 0;
    "></div>

    
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div style="
            margin-bottom:14px;
            font-size:13px;
        ">

            <div style="
                display:flex;
                justify-content:space-between;
                font-weight:bold;
            ">
                <span><?php echo e($item->menu->name); ?></span>

                <span>
                    Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                </span>
            </div>

            <div style="
                font-size:12px;
                color:#444;
            ">
                <?php echo e($item->quantity); ?> ×
                Rp <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?>

            </div>

            <?php if($item->options->isNotEmpty()): ?>
                <?php $__currentLoopData = $item->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="receipt-option-line">
                        - <?php echo e($opt->group_name); ?>: <?php echo e($opt->option_name ?? $opt->text_value); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div style="
        border-top:1px dashed black;
        margin:16px 0;
    "></div>

    
    <div style="
        display:flex;
        justify-content:space-between;
        font-size:16px;
        font-weight:bold;
    ">
        <span>TOTAL</span>

        <span>
            Rp <?php echo e(number_format($total, 0, ',', '.')); ?>

        </span>
    </div>

    <div style="
        border-top:1px dashed black;
        margin:16px 0;
    "></div>

    
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

            link.download = 'receipt-<?php echo e($order->order_code); ?>.png';

            link.href = canvas.toDataURL('image/png');

            link.click();
        });
    }
</script>


</body>
</html><?php /**PATH C:\laragon\www\newCafeApp\resources\views/customer/ordersuccess/index.blade.php ENDPATH**/ ?>