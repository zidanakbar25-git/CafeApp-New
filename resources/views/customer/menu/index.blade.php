<!DOCTYPE html>
<html>

<head>
    <title>Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite('resources/css/menu.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container py-3 app-container">

        <div class="header mb-3 d-flex justify-content-between align-items-center">

            <!-- KIRI: LOGO + NAMA -->
            <div class="d-flex align-items-center gap-2">

                <div class="logo-wrapper">
                    <img src="{{ asset('images/logo/logo.png') }}" class="logo-img">
                </div>

                <h5 class="fw-bold mb-0">Cozy Cafe</h5>
            </div>

            <!-- KANAN: MEJA + CART -->
            <div class="d-flex align-items-center gap-2">

                <span class="badge-table">
                    Meja {{ $tableData->table_number ?? '-' }}
                </span>

                <!-- CART ICON -->
                <div class="cart-header" onclick="goToCart()" dusk="cart-button">
                    <img src="{{ asset('images/icons/cart.png') }}" class="cart-icon">
                    <span id="cart-count" dusk="cart-count">0</span>
                </div>



            </div>
        </div>

        <!-- CATEGORY -->
        <div class="category-wrapper mb-3">
            @foreach($categories as $index => $cat)
            <button class="category-btn {{ $index == 0 ? 'category-active' : '' }}" data-category="{{ $cat->name }}">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <!-- SUBCATEGORY -->
        <div class="mb-3 d-flex gap-2 flex-wrap">
            @foreach($subCategories as $index => $sub)
            <button class="sub-btn {{ $index == 0 ? 'sub-active' : '' }}" data-sub="{{ $sub->name }}">
                {{ $sub->name }}
            </button>
            @endforeach
        </div>



        <!-- MENU -->
        <div class="row g-3">
            @foreach($menus as $menu)
            <div class="col-6 col-lg-4 menu-item" data-category="{{ $menu->category_name }}"
                data-sub="{{ $menu->sub_name }}">

                <div class="card menu-card shadow-sm">

                    <img src="{{ asset('storage/' . $menu->image_url) }}" class="menu-img w-100">

                    <div class="p-2">

                        <div class="fw-semibold" style="font-size: 14px;">
                            {{ $menu->name }}
                        </div>

                        <div class="text-muted" style="font-size: 12px;">
                            {{ $menu->description }}
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="price">
                                Rp {{ number_format($menu->price) }}
                            </span>

                            <button class="btn btn-add btn-sm add-to-cart" dusk="add-cart"
                                data-id="{{ $menu->menu_id }}" data-name="{{ $menu->name }}"
                                data-price="{{ $menu->price }}" data-table-id="{{ $tableData->table_id }}">

                                Add
                            </button>
                        </div>

                    </div>

                </div>

            </div>
            @endforeach
        </div>





    </div>

    <script>
       const SESSION_EXPIRES_AT = {{ $sessionData['expires_at'] }};



        // ── Filter Category & SubCategory ──────────────────────────────────────────

        let activeCategory = null;
        let activeSub = null;

        const categoryMap = {
            "Food": ["Main Course", "Dessert"],
            "Drink": ["Coffee", "Non-Coffee"]
        };

        function filterMenu() {
            document.querySelectorAll('.menu-item').forEach(item => {
                let show = true;
                if (activeCategory && item.dataset.category !== activeCategory) show = false;
                if (activeSub && item.dataset.sub !== activeSub) show = false;
                item.style.display = show ? 'block' : 'none';
            });
        }

        function filterSubCategory() {
            document.querySelectorAll('.sub-btn').forEach(btn => {
                const subName = btn.dataset.sub;
                btn.style.display = (!activeCategory || categoryMap[activeCategory]?.includes(subName)) ?
                    'inline-block' : 'none';
            });
        }

        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                activeCategory = this.dataset.category;
                activeSub = null;
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove(
                    'category-active'));
                this.classList.add('category-active');
                filterSubCategory();
                filterMenu();
            });
        });

        document.querySelectorAll('.sub-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                activeSub = this.dataset.sub;
                document.querySelectorAll('.sub-btn').forEach(b => b.classList.remove('sub-active'));
                this.classList.add('sub-active');
                filterMenu();
            });
        });

        document.querySelector('.category-btn').click();


        // ── Cart (AJAX ke database) ────────────────────────────────────────────────

        const CSRF_TOKEN = "{{ csrf_token() }}";
        const TABLE_ID = {{ $tableData->table_id }};

        let currentOrderId = {{ $order ? $order->order_id : 'null' }};

        /**
         * Ambil total qty dari DB lalu update badge
         */
        async function refreshCartBadge() {
            if (!currentOrderId) {
                document.getElementById('cart-count').style.display = 'none';
                return;
            }
            try {
                const res = await fetch(`/cart/count/${currentOrderId}`);
                const data = await res.json();
                const count = data.count ?? 0;
                const badge = document.getElementById('cart-count');
                if (count > 0) {
                    badge.innerText = count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            } catch (e) {
                console.error('Gagal refresh badge:', e);
            }
        }

        /**
         * Kirim item ke DB via AJAX, lalu refresh badge
         */
        async function addToCartAjax(menuId, btnEl) {
            if (Date.now() / 1000 > SESSION_EXPIRES_AT) {
                window.location.href = '/table/{{ $tableData->table_number }}';
                return;
            }

            btnEl.disabled = true;
            btnEl.innerText = '...';

            try {
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        menu_id: menuId,
                        table_id: TABLE_ID
                    }),
                });

                if (res.status === 401) {
                    const data = await res.json();
                    if (data.message === 'session_expired') {
                        Swal.fire({
                            title: 'Sesi Habis!',
                            text: 'Sesi kamu sudah habis. Silakan scan QR ulang.',
                            icon: 'warning',
                            confirmButtonColor: '#451a03'
                        }).then(() => {
                            window.location.href = '/table/{{ $tableData->table_number }}';
                        });
                        return;
                    }
                }

                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const data = await res.json();

                // Simpan order_id yang baru dibuat (kalau sebelumnya null)
                if (data.order_id) currentOrderId = data.order_id;

                await refreshCartBadge();

                btnEl.innerText = '✓';
                setTimeout(() => {
                    btnEl.innerText = 'Add';
                    btnEl.disabled = false;
                }, 800);

            } catch (e) {
                console.error('Gagal tambah item:', e);
                btnEl.innerText = '!';
                setTimeout(() => {
                    btnEl.innerText = 'Add';
                    btnEl.disabled = false;
                }, 1000);
            }
        }

        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function() {
                addToCartAjax(this.dataset.id, this);
            });
        });

        function goToCart() {
            if (Date.now() / 1000 > SESSION_EXPIRES_AT) {
            window.location.href = '/table/{{ $tableData->table_number }}';
            return;
        }


            if (!currentOrderId) {
                Swal.fire({
                    title: 'Kosong!',
                    text: 'Keranjang masih kosong, tambahkan item dulu.',
                    icon: 'info',
                    confirmButtonColor: '#451a03'
                });
                return;
            }
            window.location.href = `/cart/${currentOrderId}`;
        }

        // Init badge saat halaman dibuka
        refreshCartBadge();
    </script>

</body>

</html>