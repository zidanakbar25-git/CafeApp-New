<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — Cozy Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { margin: 0; background: #f5f7fb; font-family: 'Segoe UI', system-ui, sans-serif; }
        .admin-layout { display: flex; }
        .admin-content { flex: 1; padding: 30px; }

        .topbar {
            display: flex; align-items: center; gap: 10px;
            padding: 14px 30px;
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            font-size: 13px; color: #6b7280;
        }
        .topbar strong { color: #111827; }

        .stat-card {
            background: #fff; border-radius: 22px;
            padding: 22px; border: 1px solid #e5e7eb; height: 130px;
        }
        .stat-title { font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
        .stat-value { font-size: 36px; font-weight: 700; margin-top: 10px; color: #0b1533; }

        .order-wrapper {
            background: #fff; border-radius: 28px;
            border: 1px solid #e5e7eb; margin-top: 30px; overflow: hidden;
        }
        .order-header { padding: 22px; border-bottom: 1px solid #e5e7eb; }
        .order-title  { font-size: 22px; font-weight: 700; color: #0b1533; }

        .tab-btn {
            border: none; border-radius: 30px; padding: 7px 16px;
            font-weight: 600; background: #f3f4f6; color: #374151;
            font-size: 13px; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .tab-btn.active { background: #0b1533; color: #fff; }
        .tab-btn:hover:not(.active) { background: #e5e7eb; }

        .search-box {
            border-radius: 30px; border: 1px solid #d1d5db;
            padding: 9px 18px; width: 260px; outline: none; font-size: 13px;
        }

        .order-card {
            border: 1px solid #e5e7eb; border-radius: 22px;
            padding: 18px; background: #fff; transition: .2s ease;
        }
        .order-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.06); }

        .order-id   { font-size: 16px; font-weight: 700; color: #0b1533; }
        .info-label { color: #6b7280; font-size: 12px; margin-bottom: 2px; }
        .info-value { font-size: 14px; font-weight: 600; color: #0b1533; }
        .menu-item  { background: #f9fafb; border-radius: 12px; padding: 9px 13px; margin-top: 8px; font-size: 13px; }

        .badge-menunggu      { background: #fff3cd; color: #d97706; }
        .badge-diproses      { background: #dbeafe; color: #2563eb; }
        .badge-selesai       { background: #dcfce7; color: #16a34a; }
        .badge-dibatalkan    { background: #fee2e2; color: #dc2626; }
        .badge-pending_cash  { background: #fff7ed; color: #c2410c; border: 1.5px solid #fed7aa; }
        .status-badge {
            padding: 4px 10px; border-radius: 10px;
            font-size: 11px; font-weight: 700; text-transform: uppercase;
        }

        /* Banner peringatan di card tunai belum dikonfirmasi */
        .cash-pending-banner {
            display: flex; align-items: center; gap: 8px;
            background: #fff7ed; border: 1.5px solid #fed7aa;
            border-radius: 12px; padding: 9px 13px;
            margin-top: 10px; font-size: 12px; font-weight: 600; color: #9a3412;
        }

        .btn-serve        { background: #0b1533; color: #fff; border: none; border-radius: 30px; padding: 7px 15px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-done         { background: #16a34a; color: #fff; border: none; border-radius: 30px; padding: 7px 15px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-cancel       { background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 30px; padding: 7px 15px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-struk        { background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 30px; padding: 7px 15px; font-size: 13px; cursor: pointer; display:inline-flex;align-items:center;gap:5px; }
        .btn-hapus        { background: #fff; color: #dc2626; border: 1px solid #fca5a5; border-radius: 30px; padding: 7px 12px; font-size: 13px; cursor: pointer; }
        .btn-kembali      { background: #fff; color: #7c3aed; border: 1px solid #c4b5fd; border-radius: 30px; padding: 7px 15px; font-size: 13px; font-weight: 600; cursor: pointer; display:inline-flex;align-items:center;gap:5px; }
        .btn-confirm-cash { background: #ea580c; color: #fff; border: none; border-radius: 30px; padding: 7px 15px; font-size: 13px; font-weight: 600; cursor: pointer; display:inline-flex;align-items:center;gap:6px; }

        .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
        .empty-state svg { margin-bottom: 12px; opacity: .4; }
        .empty-state p  { font-size: 15px; font-weight: 600; color: #6b7280; margin: 0; }
        .empty-state small { font-size: 13px; }

        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
        .toast-msg { background: #0b1533; color: #fff; padding: 12px 20px; border-radius: 14px; font-size: 13px; font-weight: 500; box-shadow: 0 4px 16px rgba(0,0,0,.15); animation: slideIn .3s ease; }
        .toast-msg.error { background: #dc2626; }
        @keyframes slideIn { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:translateX(0); } }
    </style>
</head>

<body>
<div id="toast-container"></div>

<div class="admin-layout">

    @include('admin.layout.sidebar')

    <div class="admin-content" style="margin-left:260px;">

        <!-- Breadcrumb -->
        <div class="topbar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <span>{{ ucfirst(auth()->user()->role) }}</span>
            <span style="color:#d1d5db;">/</span>
            <strong>Dashboard</strong>
        </div>

        <div style="padding:30px;">

            <!-- Header -->
            <div style="font-size:34px;font-weight:700;color:#0b1533;">Dashboard</div>
            <div style="color:#6b7280;margin-bottom:28px;">Selamat Datang, <b>{{ auth()->user()->username }}</b></div>



            {{-- Stats hanya tampil untuk kasir / semua role KECUALI manager --}}
            @if(auth()->user()->role !== 'manager')
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stat-card d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-title">Total Pesanan</div>
                            <div class="stat-value">{{ $countAktif + $countSelesai + $countDibatalkan }}</div>
                        </div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <rect x="9" y="3" width="6" height="4" rx="1"/>
                        </svg>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-title">Aktif</div>
                            <div class="stat-value">{{ $countAktif }}</div>
                        </div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <line x1="12" y1="2" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22"/>
                            <line x1="4.93" y1="4.93" x2="6.34" y2="6.34"/>
                            <line x1="17.66" y1="17.66" x2="19.07" y2="19.07"/>
                            <line x1="2" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22" y2="12"/>
                            <line x1="4.93" y1="19.07" x2="6.34" y2="17.66"/>
                            <line x1="17.66" y1="6.34" x2="19.07" y2="4.93"/>
                        </svg>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-title">Selesai</div>
                            <div class="stat-value">{{ $countSelesai }}</div>
                        </div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <circle cx="12" cy="12" r="9"/>
                            <polyline points="9 12 11 14 15 10"/>
                        </svg>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Section -->
            <div class="order-wrapper">

                <!-- Header + Tabs -->
                <div class="order-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="order-title">Pesanan</div>

                            <a href="{{ route('admin.dashboard', ['tab' => 'aktif', 'search' => request('search')]) }}"
                               class="tab-btn {{ $tab === 'aktif' ? 'active' : '' }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                                </svg>
                                Aktif
                                <span class="{{ $tab === 'aktif' ? 'badge bg-light text-dark' : 'badge bg-secondary' }}">
                                    {{ $countAktif }}
                                </span>
                            </a>

                            <a href="{{ route('admin.dashboard', ['tab' => 'selesai', 'search' => request('search')]) }}"
                               class="tab-btn {{ $tab === 'selesai' ? 'active' : '' }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Selesai
                                <span class="{{ $tab === 'selesai' ? 'badge bg-light text-dark' : 'badge bg-secondary' }}">
                                    {{ $countSelesai }}
                                </span>
                            </a>

                            <a href="{{ route('admin.dashboard', ['tab' => 'dibatalkan', 'search' => request('search')]) }}"
                               class="tab-btn {{ $tab === 'dibatalkan' ? 'active' : '' }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"/>
                                    <line x1="15" y1="9" x2="9" y2="15"/>
                                    <line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                Dibatalkan
                                <span class="{{ $tab === 'dibatalkan' ? 'badge bg-light text-dark' : 'badge bg-secondary' }}">
                                    {{ $countDibatalkan }}
                                </span>
                            </a>
                        </div>

                        <!-- Search -->
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex gap-2">
                            <input type="hidden" name="tab" value="{{ $tab }}">
                            <input type="text" name="search" class="search-box"
                                   value="{{ request('search') }}"
                                   placeholder="Cari kode / nama pelanggan...">
                        </form>

                    </div>
                </div>

                <!-- Order Cards -->
                <div class="p-3">
                    @if($orders->isEmpty())
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                <rect x="9" y="3" width="6" height="4" rx="1"/>
                            </svg>
                            <p>Tidak ada pesanan</p>
                            <small>Pesanan baru akan muncul di sini secara otomatis.</small>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($orders as $order)
                            @php
                                // Ambil payment method: prioritas dari tabel payments, fallback ke orders
                                $payRec = $order->payments->sortByDesc('created_at')->first();
                                $bayar  = $payRec?->payment_method ?? $order->payment_method ?? null;
                            @endphp
                            <div class="col-lg-6" id="order-card-{{ $order->order_id }}">
                                <div class="order-card">

                                    <!-- Top -->
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="order-id">{{ $order->order_code }}</span>
                                                <span class="status-badge badge-{{ $order->status }}" id="badge-{{ $order->order_id }}">
                                                    {{ strtoupper($order->status) }}
                                                </span>
                                            </div>
                                            <div class="text-secondary mt-1" style="font-size:12px;">
                                                {{ $order->created_at?->setTimezone('Asia/Jakarta')->format('d M Y, H:i') ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="info-label">Total</div>
                                            <div class="info-value">Rp {{ number_format($order->total_amount,0,',','.') }}</div>
                                        </div>
                                    </div>

                                    <!-- Info -->
                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <div class="info-label">Pelanggan</div>
                                            <div class="info-value">{{ $order->customer_name ?? '-' }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="info-label">Meja</div>
                                            <div class="info-value">{{ $order->cafeTable->table_number ?? '-' }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="info-label">Bayar</div>
                                            <div class="info-value">
                                                @if($bayar)
                                                    {{ strtoupper($bayar) }}
                                                @else
                                                    <span style="color:#9ca3af;">-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Items -->
                                    @foreach($order->orderDetails as $detail)
                                    <div class="menu-item d-flex justify-content-between">
                                        <span>{{ $detail->menu->name ?? '-' }} × {{ $detail->quantity }}</span>
                                        <span>Rp {{ number_format($detail->subtotal,0,',','.') }}</span>
                                    </div>
                                    @endforeach

                                    {{-- Banner tunai belum dikonfirmasi --}}
                                    @if($order->status === 'pending_cash')
                                    <div class="cash-pending-banner">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <circle cx="12" cy="12" r="9"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        Menunggu konfirmasi penerimaan uang tunai dari kasir
                                    </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="d-flex justify-content-between align-items-center mt-3"
                                         id="actions-{{ $order->order_id }}">

                                        <div class="d-flex gap-2 flex-wrap">
                                            {{-- PENDING_CASH: Konfirmasi Tunai + Batal --}}
                                            @if($order->status === 'pending_cash')
                                                <button class="btn-confirm-cash"
                                                        onclick="confirmCash({{ $order->order_id }}, this)">
                                                    Konfirmasi Terima Uang
                                                </button>
                                                <button class="btn-cancel"
                                                        onclick="confirmCancel({{ $order->order_id }})">
                                                    ✕ Batal
                                                </button>

                                            {{-- MENUNGGU: Start Serve + Batal --}}
                                            @elseif($order->status === 'menunggu')
                                                <button class="btn-serve"
                                                        onclick="updateStatus({{ $order->order_id }}, 'diproses', this)">
                                                    ▶ Start Serve
                                                </button>
                                                <button class="btn-cancel"
                                                        onclick="confirmCancel({{ $order->order_id }})">
                                                    ✕ Batal
                                                </button>

                                            {{-- DIPROSES: Selesai + Batal --}}
                                            @elseif($order->status === 'diproses')
                                                <button class="btn-done"
                                                        onclick="updateStatus({{ $order->order_id }}, 'selesai', this)">
                                                    ✓ Selesai
                                                </button>
                                                <button class="btn-cancel"
                                                        onclick="confirmCancel({{ $order->order_id }})">
                                                    ✕ Batal
                                                </button>

                                            {{-- SELESAI: Kembalikan --}}
                                            @elseif($order->status === 'selesai')
                                                <button class="btn-kembali"
                                                        onclick="kembalikan({{ $order->order_id }})">
                                                    ↩ Kembalikan
                                                </button>

                                            @endif
                                        </div>

                                        <div class="d-flex gap-2">
                                            {{-- Struk: tampil jika sudah ada pembayaran --}}
                                            @if($order->status === 'selesai')
                                            <a href="{{ route('admin.orders.struk', $order->order_id) }}"
                                               target="_blank" class="btn-struk">
                                                
                                                <img src="{{ asset('images/icons/receipt.png') }}"
                                                    alt="Struk"
                                                    width="20">

                                            </a>
                                            @endif
                                            {{-- Hapus: selesai & dibatalkan --}}
                                            @if(in_array($order->status, ['selesai','dibatalkan']))
                                                <button class="btn-hapus"
                                                        onclick="hapusPesanan({{ $order->order_id }})">
                                                    🗑
                                                </button>
                                            @endif
                                        </div>

                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div><!-- /order-wrapper -->
        </div>
    </div>
</div>

<!-- Modal konfirmasi batal -->
<div class="modal fade" id="modalBatal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none;">
            <div class="modal-body p-4 text-center">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" class="mb-3">
                    <circle cx="12" cy="12" r="9"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <h5 style="font-weight:700;color:#0b1533;">Batalkan Pesanan?</h5>
                <p class="text-muted" style="font-size:14px;">Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Tidak</button>
                    <button class="btn btn-danger px-4" id="btnKonfirmasiBatal">Ya, Batalkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi kembalikan -->
<div class="modal fade" id="modalKembali" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none;">
            <div class="modal-body p-4 text-center">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" class="mb-3">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                </svg>
                <h5 style="font-weight:700;color:#0b1533;">Kembalikan ke Menunggu?</h5>
                <p class="text-muted" style="font-size:14px;">Status pesanan akan direset ke <strong>Menunggu</strong>.</p>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Tidak</button>
                    <button class="btn px-4" id="btnKonfirmasiKembali"
                            style="background:#7c3aed;color:#fff;">Ya, Kembalikan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
let pendingCancelId  = null;
let pendingKembaliId = null;

function showToast(msg, isError = false) {
    const container = document.getElementById('toast-container');
    const el = document.createElement('div');
    el.className = 'toast-msg' + (isError ? ' error' : '');
    el.textContent = msg;
    container.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

function updateStatus(orderId, newStatus, btn) {
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: newStatus }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✓ Status diubah ke: ' + newStatus.toUpperCase());
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal mengubah status.', true);
            btn.disabled = false;
        }
    })
    .catch(() => {
        showToast('Terjadi kesalahan jaringan.', true);
        btn.disabled = false;
    });
}

function confirmCancel(orderId) {
    pendingCancelId = orderId;
    new bootstrap.Modal(document.getElementById('modalBatal')).show();
}

document.getElementById('btnKonfirmasiBatal').addEventListener('click', function () {
    if (!pendingCancelId) return;
    bootstrap.Modal.getInstance(document.getElementById('modalBatal')).hide();

    fetch(`/admin/orders/${pendingCancelId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: 'dibatalkan' }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Pesanan berhasil dibatalkan.');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal membatalkan pesanan.', true);
        }
        pendingCancelId = null;
    })
    .catch(() => showToast('Terjadi kesalahan jaringan.', true));
});

// ==========================================
// KONFIRMASI TUNAI
// ==========================================
function confirmCash(orderId, btn) {
    Swal.fire({
        title: 'Konfirmasi Tunai',
        text: 'Konfirmasi bahwa uang tunai sudah diterima dari pelanggan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea580c',
        confirmButtonText: 'Ya, Diterima',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (!result.isConfirmed) return;

    btn.disabled = true;
    btn.textContent = 'Memproses...';

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: 'menunggu' }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Tunai dikonfirmasi — pesanan masuk antrian.');
            setTimeout(() => { window.location.href = '/dashboard'; }, 800);
        } else {
            showToast(data.message || 'Gagal konfirmasi.', true);
            btn.disabled = false;
            btn.textContent = 'Konfirmasi Terima Uang';
        }
    })
    .catch(() => {
        showToast('Terjadi kesalahan jaringan.', true);
        btn.textContent = 'Konfirmasi Terima Uang';
    });
    });
}

// ==========================================
// KEMBALIKAN
// ==========================================
function kembalikan(orderId) {
    pendingKembaliId = orderId;
    new bootstrap.Modal(document.getElementById('modalKembali')).show();
}

document.getElementById('btnKonfirmasiKembali').addEventListener('click', function () {
    if (!pendingKembaliId) return;
    bootstrap.Modal.getInstance(document.getElementById('modalKembali')).hide();

    fetch(`/admin/orders/${pendingKembaliId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: 'menunggu' }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('↩ Pesanan dikembalikan ke Menunggu.');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal mengembalikan pesanan.', true);
        }
        pendingKembaliId = null;
    })
    .catch(() => showToast('Terjadi kesalahan jaringan.', true));
});

// ==========================================
// HAPUS
// ==========================================
function hapusPesanan(orderId) {
    Swal.fire({
        title: 'Hapus Pesanan?',
        text: 'Hapus pesanan ini dari daftar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (!result.isConfirmed) return;

    fetch(`/admin/orders/${orderId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Pesanan berhasil dihapus.');
            document.getElementById(`order-card-${orderId}`)?.remove();
        } else {
            showToast(data.message, true);
        }
    })
    .catch(() => showToast('Terjadi kesalahan jaringan.', true));
    });
}

@if($tab === 'aktif')
setTimeout(() => location.reload(), 30000);
@endif
</script>
</body>
</html>