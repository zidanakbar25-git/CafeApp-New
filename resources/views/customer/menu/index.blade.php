<!DOCTYPE html>
<html>

<head>
    <title>Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    

    @vite('resources/css/menu.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap');

:root {
    --menu-ink: #2B1B12;
    --menu-cocoa: #6B4A34;
    --menu-cream: #FBF5EC;
    --menu-cream-deep: #F1E3D0;
    --menu-rust: #B5502D;
    --menu-rust-dark: #8F3D22;
    --menu-error: #C1442E;
}

#menuOptionModal .opt-modal {
    border: none;
    border-radius: 24px;
    overflow: visible;
    background: var(--menu-cream);
    box-shadow: 0 24px 60px rgba(43, 27, 18, 0.25);
}

#menuOptionModal .opt-modal__close {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 3;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: rgba(251, 245, 236, 0.95);
    background-image: none;
    opacity: 1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

#menuOptionModal .opt-modal__close::before {
    content: '×';
    font-size: 22px;
    line-height: 1;
    color: var(--menu-ink);
    font-weight: 400;
}

#menuOptionModal .opt-modal__frame {
    padding: 10px 10px 0;
    border-radius: 24px 24px 0 0;
    overflow: hidden;
}

#menuOptionModal .opt-modal__image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 18px;
    display: block;
    border: 3px solid var(--menu-cream);
    box-shadow: 0 4px 14px rgba(43,27,18,0.18);
}

#menuOptionModal .opt-modal__title-card {
    position: relative;
    margin: -22px 20px 4px;
    background: #fff;
    border-radius: 16px;
    padding: 14px 18px;
    box-shadow: 0 8px 22px rgba(43,27,18,0.14);
    z-index: 2;
}

#menuOptionModal .opt-modal__title {
    font-family: 'Fraunces', serif;
    font-weight: 600;
    font-size: 19px;
    color: var(--menu-ink);
    margin: 0;
}

#menuOptionModal .opt-modal__body {
    padding: 8px 22px 4px;
    max-height: 50vh;
    overflow-y: auto;
}

.opt-group {
    background: #fff;
    border: 1px solid var(--menu-cream-deep);
    border-radius: 16px;
    padding: 14px 16px 16px;
    margin-bottom: 14px;
}

.opt-group__label {
    font-family: 'Fraunces', serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--menu-ink);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}

.opt-group__required {
    font-size: 10px;
    font-weight: 700;
    color: var(--menu-rust);
    background: rgba(181, 80, 45, 0.1);
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
}

.opt-choices {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.opt-row {
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1.5px solid var(--menu-cream-deep);
    border-radius: 14px;
    padding: 12px 16px;
    cursor: pointer;
    background: #fff;
    transition: border-color 0.15s ease, background 0.15s ease;
    user-select: none;
}

.opt-row:hover {
    border-color: var(--menu-rust);
}

.opt-row:has(input:checked) {
    border-color: var(--menu-rust);
    background: rgba(181, 80, 45, 0.06);
}

.opt-row__input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.opt-row__indicator {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    border: 2px solid var(--menu-cream-deep);
    position: relative;
    transition: border-color 0.15s ease;
}

.opt-row__indicator--radio {
    border-radius: 50%;
}

.opt-row__indicator--checkbox {
    border-radius: 6px;
}

.opt-row__input:checked + .opt-row__indicator {
    border-color: var(--menu-rust);
}

.opt-row__input:checked + .opt-row__indicator--radio::after {
    content: '';
    position: absolute;
    inset: 4px;
    border-radius: 50%;
    background: var(--menu-rust);
}

.opt-row__input:checked + .opt-row__indicator--checkbox::after {
    content: '';
    position: absolute;
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid var(--menu-rust);
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.opt-row__input:focus-visible + .opt-row__indicator {
    outline: 2px solid var(--menu-rust);
    outline-offset: 2px;
}

.opt-row__text {
    flex: 1;
    font-size: 14px;
    color: var(--menu-ink);
    font-weight: 500;
}

.opt-textarea {
    width: 100%;
    border: 1.5px solid var(--menu-cream-deep);
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 13px;
    color: var(--menu-ink);
    background: var(--menu-cream);
    resize: vertical;
    outline: none;
    transition: all 0.15s ease;
}

.opt-textarea:focus {
    background: #fff;
    border-color: var(--menu-rust);
    box-shadow: 0 0 0 3px rgba(181, 80, 45, 0.12);
}

.opt-error {
    color: var(--menu-error);
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
    display: none;
}

#menuOptionModal .opt-modal__footer {
    padding: 12px 22px 22px;
    border: none;
    background: transparent;
}

#menuOptionModal .opt-modal__confirm {
    width: 100%;
    border: none;
    border-radius: 30px;
    background: var(--menu-rust);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    padding: 13px;
    letter-spacing: 0.2px;
    transition: background 0.15s ease;
}

#menuOptionModal .opt-modal__confirm:hover {
    background: var(--menu-rust-dark);
}

@media (prefers-reduced-motion: reduce) {
    #menuOptionModal * { transition: none !important; }
}

#toast-container {
    position: fixed;
    top: 16px;
    right: 16px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
}

.toast-item {
    background: var(--menu-ink, #2B1B12);
    color: #fff;
    padding: 12px 18px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    display: flex;
    align-items: center;
    gap: 8px;
    transform: translateY(-16px);
    opacity: 0;
    transition: transform 0.25s ease, opacity 0.25s ease;
    max-width: 260px;
}

.toast-item.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-item__icon {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--menu-rust, #B5502D);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

@media (prefers-reduced-motion: reduce) {
    .toast-item { transition: none; }
}

</style>


</head>

<body>

<div id="toast-container"></div>

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
                                data-price="{{ $menu->price }}" data-table-id="{{ $tableData->table_id }}"
                                data-image="{{ asset('storage/' . $menu->image_url) }}">
                                
                                Add
                            </button>
                        </div>

                    </div>

                </div>

            </div>
            @endforeach
        </div>





    </div>


  <div class="modal fade" id="menuOptionModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content opt-modal">
      <button type="button" class="btn-close opt-modal__close" data-bs-dismiss="modal" aria-label="Tutup"></button>

      <div class="opt-modal__frame">
        <img id="modalMenuImage" src="" alt="" class="opt-modal__image">
      </div>

      <div class="opt-modal__title-card">
        <h5 class="opt-modal__title" id="modalMenuName">Menu</h5>
      </div>

      <div class="opt-modal__body" id="modalOptionsBody"></div>

      <div class="opt-modal__footer">
        <button type="button" class="opt-modal__confirm" id="modalConfirmBtn">
          Tambah ke Keranjang
        </button>
      </div>
    </div>
  </div>
</div>

    <script>
       const SESSION_EXPIRES_AT = {{ $sessionData['expires_at'] }};

       // Data opsi menu, dikelompokkan per menu_id (kosong [] kalau menu tidak punya opsi)
const MENU_OPTIONS = @json($menus->pluck('option_groups', 'menu_id'));

let currentModalMenuId = null;
let currentModalBtn = null;


function showToast(message) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast-item';
    toast.innerHTML = `<span class="toast-item__icon">✓</span><span>${message}</span>`;
    container.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('show'));

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 250);
    }, 2200);
}

function openOptionModal(menuId, menuName, menuImage, btnEl) {
    currentModalMenuId = menuId;
    currentModalBtn = btnEl;
    document.getElementById('modalMenuName').innerText = menuName;
    document.getElementById('modalMenuImage').src = menuImage;

    const groups = MENU_OPTIONS[menuId] || [];
    const body = document.getElementById('modalOptionsBody');
    body.innerHTML = '';

    groups.forEach((group, gIndex) => {
        const wrap = document.createElement('div');
        wrap.className = 'opt-group';
        wrap.dataset.groupName = group.name;
        wrap.dataset.inputType = group.input_type;
        wrap.dataset.required = group.is_required ? '1' : '0';

        const label = document.createElement('div');
        label.className = 'opt-group__label';
        label.innerHTML = group.name + (group.is_required ? ' <span class="opt-group__required">Wajib</span>' : '');
        wrap.appendChild(label);

        if (group.input_type === 'text') {
            const input = document.createElement('textarea');
            input.className = 'opt-textarea';
            input.placeholder = group.placeholder || '';
            input.rows = 2;
            input.addEventListener('input', function () {
                if (this.value.trim()) hideGroupError(wrap);
            });
            wrap.appendChild(input);
        } else {
            const choices = document.createElement('div');
            choices.className = 'opt-choices';

            group.options.forEach((optName, oIndex) => {
                const row = document.createElement('label');
                row.className = 'opt-row';

                const input = document.createElement('input');
                input.type = group.input_type === 'radio' ? 'radio' : 'checkbox';
                input.name = `group_${gIndex}`;
                input.value = optName;
                input.id = `group_${gIndex}_opt_${oIndex}`;
                input.className = 'option-choice-input opt-row__input';
                input.addEventListener('change', function () {
                    hideGroupError(wrap);
                });

                const indicator = document.createElement('span');
                indicator.className = 'opt-row__indicator ' +
                    (group.input_type === 'radio' ? 'opt-row__indicator--radio' : 'opt-row__indicator--checkbox');

                const text = document.createElement('span');
                text.className = 'opt-row__text';
                text.innerText = optName;

                row.appendChild(input);
                row.appendChild(indicator);
                row.appendChild(text);
                choices.appendChild(row);
            });

            wrap.appendChild(choices);
        }

        const errorMsg = document.createElement('div');
        errorMsg.className = 'opt-error option-error-msg';
        errorMsg.innerText = 'Wajib dipilih.';
        wrap.appendChild(errorMsg);

        body.appendChild(wrap);
    });

    new bootstrap.Modal(document.getElementById('menuOptionModal')).show();
}

function hideGroupError(wrap) {
    const msg = wrap.querySelector('.option-error-msg');
    if (msg) msg.style.display = 'none';
}

document.getElementById('modalConfirmBtn').addEventListener('click', function () {
    const body = document.getElementById('modalOptionsBody');
    const selections = [];
    let valid = true;

    body.querySelectorAll('[data-group-name]').forEach(wrap => {
        const groupName = wrap.dataset.groupName;
        const inputType = wrap.dataset.inputType;
        const required = wrap.dataset.required === '1';
        const errorMsg = wrap.querySelector('.option-error-msg');

        if (inputType === 'text') {
            const val = wrap.querySelector('.option-text-input').value.trim();
            if (required && !val) {
                valid = false;
                if (errorMsg) errorMsg.style.display = 'block';
                return;
            }
            if (val) selections.push({ group_name: groupName, input_type: 'text', text_value: val });
        } else {
            const checked = wrap.querySelectorAll('.option-choice-input:checked');
            if (required && checked.length === 0) {
                valid = false;
                if (errorMsg) errorMsg.style.display = 'block';
                return;
            }
            checked.forEach(inp => {
                selections.push({ group_name: groupName, input_type: inputType, option_name: inp.value });
            });
        }
    });

    if (!valid) return; // teks merah sudah muncul di grup yang belum diisi, cukup berhenti di sini

    bootstrap.Modal.getInstance(document.getElementById('menuOptionModal')).hide();
    addToCartAjax(currentModalMenuId, currentModalBtn, selections);
});

document.getElementById('menuOptionModal').addEventListener('hidden.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
    document.body.style.removeProperty('overflow');
});


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

        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function() {
                const menuId = this.dataset.id;
                const menuName = this.dataset.name;
                const menuImage = this.dataset.image;
                const groups = MENU_OPTIONS[menuId] || [];

                if (groups.length > 0) {
                    openOptionModal(menuId, menuName, menuImage, this);
                } else {
                    addToCartAjax(menuId, this);
                }
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
        async function addToCartAjax(menuId, btnEl, options = []) {
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
                        table_id: TABLE_ID,
                        options: options
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
                showToast('Ditambahkan ke keranjang');

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