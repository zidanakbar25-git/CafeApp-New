<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Manual — Cozy Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            background: #f5f7fb;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .admin-layout {
            display: flex;
        }

        .admin-content {
            flex: 1;
            padding: 0;
            margin-left: 260px;
        }

        .topbar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 30px;
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            font-size: 13px;
            color: #6b7280;
        }

        .topbar strong {
            color: #111827;
        }

        .mo-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 22px;
            padding: 26px 30px 30px;
            align-items: start;
        }

        .mo-title {
            font-size: 30px;
            font-weight: 700;
            color: #0b1533;
        }

        .mo-sub {
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* ── Category / subcategory filter ── */
        .mo-cat-btn,
        .mo-sub-btn {
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #374151;
            border-radius: 30px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .mo-cat-btn.active,
        .mo-sub-btn.active {
            background: #0b1533;
            color: #fff;
            border-color: #0b1533;
        }

        /* ── Menu grid ── */
        .mo-menu-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .mo-menu-card img {
            width: 100%;
            height: 110px;
            object-fit: cover;
        }

        .mo-menu-card .body {
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .mo-menu-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #0b1533;
        }

        .mo-menu-price {
            font-size: 13px;
            font-weight: 700;
            color: #0b1533;
            margin-top: 4px;
        }

        .mo-add-btn {
            margin-top: auto;
            padding-top: 10px;
            border: none;
            background: #0b1533;
            color: #fff;
            border-radius: 30px;
            padding: 7px 0;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
        }

        .mo-add-btn:hover {
            background: #1e2f5e;
        }

        .mo-add-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* ── Cart sidebar ── */
        .mo-cart {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            position: sticky;
            top: 20px;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 40px);
        }

        .mo-cart-header {
            padding: 18px 20px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 16px;
            font-weight: 700;
            color: #0b1533;
        }

        .mo-cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 12px 16px;
        }

        .mo-cart-empty {
            text-align: center;
            padding: 40px 10px;
            color: #9ca3af;
            font-size: 13px;
        }

        .mo-cart-item {
            border-bottom: 1px solid #f3f4f6;
            padding: 12px 2px;
            display: flex;
            gap: 10px;
        }

        .mo-cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .mo-cart-item-name {
            font-size: 13px;
            font-weight: 700;
            color: #0b1533;
        }

        .mo-cart-item-options {
            margin-top: 3px;
            padding-left: 8px;
            border-left: 2px solid #e5e7eb;
        }

        .mo-cart-item-options span {
            display: block;
            font-size: 11px;
            color: #6b7280;
            line-height: 1.5;
        }

        .mo-cart-item-sub {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .mo-cart-item-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }

        .mo-qty-controls {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .mo-qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            cursor: pointer;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b1533;
        }

        .mo-qty-val {
            font-size: 13px;
            font-weight: 700;
            min-width: 16px;
            text-align: center;
        }

        .mo-remove-btn {
            border: none;
            background: none;
            color: #dc2626;
            font-size: 12px;
            cursor: pointer;
        }

        .mo-cart-footer {
            padding: 16px 20px 20px;
            border-top: 1px solid #f3f4f6;
        }

        .mo-total-row {
            display: flex;
            justify-content: space-between;
            font-size: 15px;
            font-weight: 700;
            color: #0b1533;
            margin-bottom: 12px;
        }

        .mo-checkout-btn {
            width: 100%;
            border: none;
            background: #0b1533;
            color: #fff;
            border-radius: 30px;
            padding: 13px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .mo-checkout-btn:hover {
            background: #1e2f5e;
        }

        .mo-checkout-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* ── Option modal (opsi menu) ── */
        .mo-opt-row {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px 14px;
            cursor: pointer;
            margin-bottom: 8px;
        }

        .mo-opt-row:has(input:checked) {
            border-color: #0b1533;
            background: #f5f7fb;
        }

        .mo-opt-row input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .mo-opt-indicator {
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            flex-shrink: 0;
            position: relative;
        }

        .mo-opt-indicator.radio {
            border-radius: 50%;
        }

        .mo-opt-indicator.checkbox {
            border-radius: 5px;
        }

        .mo-opt-row input:checked+.mo-opt-indicator {
            border-color: #0b1533;
        }

        .mo-opt-row input:checked+.mo-opt-indicator.radio::after {
            content: '';
            position: absolute;
            inset: 3px;
            border-radius: 50%;
            background: #0b1533;
        }

        .mo-opt-row input:checked+.mo-opt-indicator.checkbox::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1px;
            width: 4px;
            height: 9px;
            border: solid #0b1533;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .mo-opt-error {
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
            display: none;
        }

        /* ── Success modal ── */
        .mo-success-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #dcfce7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            color: #16a34a;
            font-size: 28px;
        }
    </style>
</head>

<body>

    <div class="admin-layout">
        @include('admin.layout.sidebar')

        <div class="admin-content">
            <div class="topbar">
                <i class="fa-solid fa-bag-shopping text-secondary"></i>
                <span>Kasir</span>
                <span style="color:#d1d5db;">/</span>
                <strong>Order Manual</strong>
            </div>

            <div class="mo-layout">

                {{-- ── Menu Panel ── --}}
                <div>
                    <div class="mo-title">Order Manual</div>
                    <div class="mo-sub">Untuk pesanan takeaway yang diinput langsung oleh kasir.</div>

                    <div class="mb-3" id="mo-cat-wrap">
                        <button class="mo-cat-btn active" data-cat-id="" data-cat-name="">Semua</button>
                        @foreach($categories as $cat)
                        <button class="mo-cat-btn" data-cat-id="{{ $cat->category_id }}" data-cat-name="{{ $cat->name }}">
                            {{ $cat->name }}
                        </button>
                        @endforeach
                    </div>

                    <div class="mb-4" id="mo-sub-wrap">
                        @foreach($subCategories as $sub)
                        <button class="mo-sub-btn" style="display:none;"
                            data-parent-id="{{ $sub->category_id }}" data-sub-name="{{ $sub->name }}">
                            {{ $sub->name }}
                        </button>
                        @endforeach
                    </div>

                    <div class="row g-3" id="mo-menu-grid">
                        @foreach($menus as $menu)
                        <div class="col-6 col-lg-4 mo-menu-item"
                            data-cat-name="{{ $menu->category_name }}" data-sub-name="{{ $menu->sub_name }}">
                            <div class="mo-menu-card">
                                <img src="{{ asset('storage/' . $menu->image_url) }}" alt="{{ $menu->name }}">
                                <div class="body">
                                    <div class="mo-menu-name">{{ $menu->name }}</div>
                                    <div class="mo-menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                                    <button class="mo-add-btn add-manual-item"
                                        data-id="{{ $menu->menu_id }}"
                                        data-name="{{ $menu->name }}">
                                        + Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Cart Sidebar ── --}}
                <div class="mo-cart">
                    <div class="mo-cart-header">Keranjang</div>
                    <div class="mo-cart-items" id="mo-cart-items"></div>
                    <div class="mo-cart-footer">
                        <div class="mo-total-row">
                            <span>Total</span>
                            <span id="mo-cart-total">Rp 0</span>
                        </div>
                        <button class="mo-checkout-btn" id="mo-checkout-btn" disabled>Checkout</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Modal Opsi Menu ── --}}
    <div class="modal fade" id="moOptionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px; border:none;">
                <div class="modal-header">
                    <h5 class="modal-title" id="moOptionMenuName">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="moOptionBody"></div>
                <div class="modal-footer">
                    <button type="button" class="mo-checkout-btn" id="moOptionConfirmBtn" style="margin:0;">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Checkout ── --}}
    <div class="modal fade" id="moCheckoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px; border:none;">
                <div class="modal-header">
                    <h5 class="modal-title">Selesaikan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold" style="font-size:13px;">Nama Customer</label>
                    <input type="text" id="moCustomerName" class="form-control mb-3" placeholder="Masukkan nama customer" style="border-radius:12px; padding:10px 14px;">

                    <label class="form-label fw-semibold" style="font-size:13px;">Metode Pembayaran</label>
                    <div id="moPaymentOptions">
                        <label class="mo-opt-row">
                            <input type="radio" name="mo_payment" value="cash" checked>
                            <span class="mo-opt-indicator radio"></span>
                            <span>Tunai</span>
                        </label>
                        <label class="mo-opt-row">
                            <input type="radio" name="mo_payment" value="qris">
                            <span class="mo-opt-indicator radio"></span>
                            <span>QRIS</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mo-checkout-btn" id="moCheckoutConfirmBtn" style="margin:0;">
                        Buat Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal QRIS ── --}}
<div class="modal fade" id="moQrisModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:20px; border:none;">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran QRIS</h5>
      </div>
      <div class="modal-body text-center">
        <img src="{{ asset('images/Qris/Qris.jpeg') }}" alt="QRIS" style="width:220px; border-radius:16px;">
        <p style="margin-top:14px; font-size:13px; color:#6b7280;">
            Minta customer scan menggunakan DANA, OVO, GoPay,
            Mobile Banking, atau E-Wallet lainnya
        </p>
        <div style="font-size:15px; font-weight:700; color:#0b1533; margin-top:6px;" id="moQrisTotal"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="mo-checkout-btn" id="moQrisFinishBtn" style="margin:0;">
            Pesanan Selesai
        </button>
      </div>
    </div>
  </div>
</div>

    {{-- ── Modal Sukses ── --}}
    <div class="modal fade" id="moSuccessModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius:22px; border:none;">
                <div class="modal-body text-center p-4">
                    <div class="mo-success-icon"><i class="fa-solid fa-check"></i></div>
                    <h5 style="font-weight:700; color:#0b1533;">Pesanan Berhasil Dibuat</h5>
                    <div id="moSuccessBody" class="text-start mt-3" style="font-size:13px;"></div>
                    <button type="button" class="mo-checkout-btn mt-3" id="moSuccessCloseBtn">Pesanan Baru</button>
                </div>
            </div>
        </div>
    </div>

    @php
    $moOrderItemsForJs = $order
        ? $order->orderDetails->map(function ($d) {
            return [
                'detail_id' => $d->detail_id,
                'name'      => $d->menu->name ?? '-',
                'quantity'  => $d->quantity,
                'subtotal'  => $d->subtotal,
                'options'   => $d->options->map(fn($o) => [
                    'group_name' => $o->group_name,
                    'value'      => $o->option_name ?? $o->text_value,
                ]),
            ];
        })
        : collect();
@endphp

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Data opsi menu per menu_id
        const MO_MENU_OPTIONS = @json($menus->pluck('option_groups', 'menu_id'));

        let moCurrentMenuId = null;

        // ── Filter kategori / subkategori ──
        document.querySelectorAll('.mo-cat-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.mo-cat-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const catId = this.dataset.catId;
                const catName = this.dataset.catName;

                document.querySelectorAll('.mo-sub-btn').forEach(sb => {
                    sb.classList.remove('active');
                    sb.style.display = (catId && sb.dataset.parentId === catId) ? 'inline-block' : 'none';
                });

                filterMoMenu(catName, null);
            });
        });

        document.querySelectorAll('.mo-sub-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.mo-sub-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const activeCat = document.querySelector('.mo-cat-btn.active').dataset.catName;
                filterMoMenu(activeCat, this.dataset.subName);
            });
        });

        function filterMoMenu(catName, subName) {
            document.querySelectorAll('.mo-menu-item').forEach(item => {
                let show = true;
                if (catName && item.dataset.catName !== catName) show = false;
                if (subName && item.dataset.subName !== subName) show = false;
                item.style.display = show ? '' : 'none';
            });
        }

        // ── Tambah item ke keranjang ──
        document.querySelectorAll('.add-manual-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const menuId = this.dataset.id;
                const menuName = this.dataset.name;
                const groups = MO_MENU_OPTIONS[menuId] || [];

                if (groups.length > 0) {
                    openMoOptionModal(menuId, menuName);
                } else {
                    addManualItem(menuId, []);
                }
            });
        });

        function openMoOptionModal(menuId, menuName) {
            moCurrentMenuId = menuId;
            document.getElementById('moOptionMenuName').innerText = menuName;

            const groups = MO_MENU_OPTIONS[menuId] || [];
            const body = document.getElementById('moOptionBody');
            body.innerHTML = '';

            groups.forEach((group, gIndex) => {
                const wrap = document.createElement('div');
                wrap.className = 'mb-3';
                wrap.dataset.groupName = group.name;
                wrap.dataset.inputType = group.input_type;
                wrap.dataset.required = group.is_required ? '1' : '0';

                const label = document.createElement('div');
                label.className = 'fw-semibold mb-2';
                label.style.fontSize = '13px';
                label.innerText = group.name + (group.is_required ? ' *' : '');
                wrap.appendChild(label);

                if (group.input_type === 'text') {
                    const input = document.createElement('textarea');
                    input.className = 'form-control mo-text-input';
                    input.rows = 2;
                    input.placeholder = group.placeholder || '';
                    input.style.borderRadius = '12px';
                    input.addEventListener('input', function() {
                        hideMoError(wrap);
                    });
                    wrap.appendChild(input);
                } else {
                    group.options.forEach((optName, oIndex) => {
                        const row = document.createElement('label');
                        row.className = 'mo-opt-row';

                        const input = document.createElement('input');
                        input.type = group.input_type === 'radio' ? 'radio' : 'checkbox';
                        input.name = `mo_group_${gIndex}`;
                        input.value = optName;
                        input.className = 'mo-choice-input';
                        input.addEventListener('change', function() {
                            hideMoError(wrap);
                        });

                        const indicator = document.createElement('span');
                        indicator.className = 'mo-opt-indicator ' + (group.input_type === 'radio' ? 'radio' : 'checkbox');

                        const text = document.createElement('span');
                        text.innerText = optName;

                        row.appendChild(input);
                        row.appendChild(indicator);
                        row.appendChild(text);
                        wrap.appendChild(row);
                    });
                }

                const err = document.createElement('div');
                err.className = 'mo-opt-error';
                err.innerText = 'Wajib dipilih.';
                wrap.appendChild(err);

                body.appendChild(wrap);
            });

            new bootstrap.Modal(document.getElementById('moOptionModal')).show();
        }

        function hideMoError(wrap) {
            const err = wrap.querySelector('.mo-opt-error');
            if (err) err.style.display = 'none';
        }

        document.getElementById('moOptionConfirmBtn').addEventListener('click', function() {
            const body = document.getElementById('moOptionBody');
            const selections = [];
            let valid = true;

            body.querySelectorAll('[data-group-name]').forEach(wrap => {
                const groupName = wrap.dataset.groupName;
                const inputType = wrap.dataset.inputType;
                const required = wrap.dataset.required === '1';
                const err = wrap.querySelector('.mo-opt-error');

                if (inputType === 'text') {
                    const val = wrap.querySelector('.mo-text-input').value.trim();
                    if (required && !val) {
                        valid = false;
                        if (err) err.style.display = 'block';
                        return;
                    }
                    if (val) selections.push({
                        group_name: groupName,
                        input_type: 'text',
                        text_value: val
                    });
                } else {
                    const checked = wrap.querySelectorAll('.mo-choice-input:checked');
                    if (required && checked.length === 0) {
                        valid = false;
                        if (err) err.style.display = 'block';
                        return;
                    }
                    checked.forEach(inp => {
                        selections.push({
                            group_name: groupName,
                            input_type: inputType,
                            option_name: inp.value
                        });
                    });
                }
            });

            if (!valid) return;

            bootstrap.Modal.getInstance(document.getElementById('moOptionModal')).hide();
            addManualItem(moCurrentMenuId, selections);
        });

        // ── AJAX tambah item ──
        async function addManualItem(menuId, options) {
            try {
                const res = await fetch('{{ route("admin.manualOrder.addItem") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        menu_id: menuId,
                        options
                    }),
                });
                const data = await res.json();

                if (!data.success) {
                    Swal.fire({
                        title: 'Gagal',
                        text: data.message,
                        icon: 'warning',
                        confirmButtonColor: '#0b1533'
                    });
                    return;
                }

            moCurrentOrderId = data.order_id;
            renderCart(data.items, data.total);
            } catch (e) {
                console.error(e);
            }
        }

        // ── Render keranjang ──
        function fmt(num) {
            return 'Rp ' + parseInt(num).toLocaleString('id-ID');
        }

        function renderCart(items, total) {
            const container = document.getElementById('mo-cart-items');
            const checkoutBtn = document.getElementById('mo-checkout-btn');

            if (!items || items.length === 0) {
                container.innerHTML = '<div class="mo-cart-empty">Keranjang masih kosong.</div>';
                document.getElementById('mo-cart-total').innerText = fmt(0);
                checkoutBtn.disabled = true;
                return;
            }

            container.innerHTML = '';
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'mo-cart-item';

                let optsHtml = '';
                if (item.options && item.options.length > 0) {
                    optsHtml = '<div class="mo-cart-item-options">' +
                        item.options.map(o => `<span>${o.group_name}: ${o.value}</span>`).join('') +
                        '</div>';
                }

                div.innerHTML = `
            <div class="mo-cart-item-info">
                <div class="mo-cart-item-name">${item.name}</div>
                ${optsHtml}
                <div class="mo-cart-item-sub">${fmt(item.subtotal)}</div>
            </div>
            <div class="mo-cart-item-right">
                <button class="mo-remove-btn" data-detail="${item.detail_id}">Hapus</button>
                <div class="mo-qty-controls">
                    <button class="mo-qty-btn" data-detail="${item.detail_id}" data-action="decrement">−</button>
                    <span class="mo-qty-val">${item.quantity}</span>
                    <button class="mo-qty-btn" data-detail="${item.detail_id}" data-action="increment">+</button>
                </div>
            </div>
        `;
                container.appendChild(div);
            });

            document.getElementById('mo-cart-total').innerText = fmt(total);
            checkoutBtn.disabled = false;

            attachCartItemHandlers();
        }

        function attachCartItemHandlers() {
            document.querySelectorAll('.mo-qty-btn').forEach(btn => {
                btn.onclick = async function() {
                    const detailId = this.dataset.detail;
                    const action = this.dataset.action;
                    const res = await fetch('/cart/update-qty-ajax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            detail_id: detailId,
                            order_id: '{{ $order->order_id ?? "" }}' || moCurrentOrderId,
                            action
                        }),
                    });
                    const data = await res.json();
                    this.parentElement.querySelector('.mo-qty-val').innerText = data.quantity;
                    this.closest('.mo-cart-item').querySelector('.mo-cart-item-sub').innerText = fmt(data.subtotal);
                    document.getElementById('mo-cart-total').innerText = fmt(data.total);
                };
            });

            document.querySelectorAll('.mo-remove-btn').forEach(btn => {
                btn.onclick = async function() {
                    const detailId = this.dataset.detail;
                    const res = await fetch('/cart/delete-ajax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            detail_id: detailId,
                            order_id: moCurrentOrderId
                        }),
                    });
                    const data = await res.json();
                    this.closest('.mo-cart-item').remove();
                    document.getElementById('mo-cart-total').innerText = fmt(data.total);
                    if (data.isEmpty) renderCart([], 0);
                    else document.getElementById('mo-checkout-btn').disabled = false;
                };
            });
        }

        // ── Checkout ──
        document.getElementById('mo-checkout-btn').addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('moCheckoutModal')).show();
        });

        document.getElementById('moCheckoutConfirmBtn').addEventListener('click', async function () {
    const name = document.getElementById('moCustomerName').value.trim();
    const method = document.querySelector('input[name="mo_payment"]:checked').value;

    if (!name) {
        Swal.fire({ title: 'Nama wajib diisi', icon: 'warning', confirmButtonColor: '#0b1533' });
        return;
    }

    this.disabled = true;
    this.innerText = 'Memproses...';

    try {
        const res = await fetch('{{ route("admin.manualOrder.checkout") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ customer_name: name, payment_method: method }),
        });
        const data = await res.json();

        if (!data.success) {
            Swal.fire({ title: 'Gagal', text: data.message, icon: 'error', confirmButtonColor: '#0b1533' });
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('moCheckoutModal')).hide();

        if (data.completed) {
            // Tunai: langsung selesai
            showMoSuccess(data.order);
        } else {
            // QRIS: tampilkan layar QRIS dulu
            document.getElementById('moQrisTotal').innerText = fmt(data.total);
            new bootstrap.Modal(document.getElementById('moQrisModal')).show();
        }

    } catch (e) {
        console.error(e);
    } finally {
        this.disabled = false;
        this.innerText = 'Buat Pesanan';
    }
});

document.getElementById('moQrisFinishBtn').addEventListener('click', async function () {
    this.disabled = true;
    this.innerText = 'Memproses...';

    try {
        const res = await fetch('{{ route("admin.manualOrder.qrisFinalize") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        });
        const data = await res.json();

        if (!data.success) {
            Swal.fire({ title: 'Gagal', text: data.message, icon: 'error', confirmButtonColor: '#0b1533' });
            this.disabled = false;
            this.innerText = 'Pesanan Selesai';
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('moQrisModal')).hide();
        showMoSuccess(data.order);

    } catch (e) {
        console.error(e);
        this.disabled = false;
        this.innerText = 'Pesanan Selesai';
    }
});

        function showMoSuccess(order) {
            const body = document.getElementById('moSuccessBody');

            let itemsHtml = order.items.map(it => {
                let optsHtml = '';
                if (it.options && it.options.length > 0) {
                    optsHtml = '<div style="padding-left:8px; border-left:2px solid #e5e7eb; margin-top:2px;">' +
                        it.options.map(o => `<span style="display:block; font-size:11px; color:#6b7280;">${o.group_name}: ${o.value}</span>`).join('') +
                        '</div>';
                }
                return `<div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                    <div>
                        <div style="font-weight:600;">${it.name} × ${it.quantity}</div>
                        ${optsHtml}
                    </div>
                    <div>${fmt(it.subtotal)}</div>
                </div>`;
            }).join('');

            body.innerHTML = `
        <div style="border-bottom:1px dashed #e5e7eb; padding-bottom:10px; margin-bottom:10px;">
            <div><strong>Kode:</strong> ${order.order_code}</div>
            <div><strong>Customer:</strong> ${order.customer_name}</div>
            <div><strong>Metode:</strong> ${order.payment_method}</div>
        </div>
        ${itemsHtml}
        <div style="border-top:1px dashed #e5e7eb; margin-top:10px; padding-top:10px; display:flex; justify-content:space-between; font-weight:700;">
            <span>Total</span><span>${fmt(order.total)}</span>
        </div>
    `;

            new bootstrap.Modal(document.getElementById('moSuccessModal')).show();
        }

        document.getElementById('moSuccessCloseBtn').addEventListener('click', function() {
            window.location.reload();
        });

        // ── Hydrate keranjang saat halaman dibuka (kalau sudah ada draft sebelumnya) ──
        let moCurrentOrderId = @json($order ? $order->order_id : null);

        renderCart(@json($moOrderItemsForJs), @json($order->total_amount ?? 0));
    </script>

</body>

</html>