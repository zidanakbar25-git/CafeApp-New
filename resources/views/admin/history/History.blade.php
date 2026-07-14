<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>History Pesanan — Cozy Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background: #f5f7fb;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .admin-layout {
            display: flex;
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

        .page-wrapper {
            background: #fff;
            border-radius: 28px;
            border: 1px solid #e5e7eb;
            margin-top: 30px;
            overflow: hidden;
        }

        .page-header {
            padding: 24px 28px;
            border-bottom: 1px solid #e5e7eb;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #0b1533;
            margin: 0;
        }

        .page-sub {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }

        .search-box {
            border-radius: 30px;
            border: 1px solid #d1d5db;
            padding: 9px 18px;
            width: 300px;
            outline: none;
            font-size: 13px;
            background: #f9fafb;
        }

        .search-box:focus {
            border-color: #0b1533;
            background: #fff;
        }

        /* Table */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .history-table thead th {
            padding: 12px 12px;
            background: #f9fafb;
            color: #6b7280;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .history-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background .15s;
        }

        .history-table tbody tr:hover {
            background: #f9fafb;
        }

        .history-table tbody td {
            padding: 12px 12px;
            color: #374151;
            vertical-align: middle;
        }

        .order-code {
            font-weight: 700;
            color: #0b1533;
            font-size: 13px;
        }

        .meja-badge {
            background: #f3f4f6;
            color: #374151;
            border-radius: 8px;
            padding: 3px 9px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .items-list {
            max-width: 180px;
            line-height: 1.5;
        }

        .item-entry {
            font-size: 12px;
            color: #374151;
        }

        .item-entry span {
            color: #9ca3af;
        }

        .total-amt {
            font-weight: 700;
            color: #0b1533;
            white-space: nowrap;
        }

        .badge-selesai {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-dibatalkan {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            display: inline-block;
        }

        .btn-struk {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .btn-struk:hover {
            background: #f3f4f6;
            color: #0b1533;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #9ca3af;
        }

        .empty-state p {
            font-size: 15px;
            font-weight: 600;
            color: #6b7280;
            margin: 8px 0 0;
        }

        .empty-state small {
            font-size: 13px;
        }

        .summary-bar {
            display: flex;
            gap: 24px;
            align-items: center;
            padding: 14px 28px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            color: #6b7280;
        }

        .summary-bar strong {
            color: #0b1533;
        }

        .pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 14px 28px;
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
}

.pagination-info {
    font-size: 12px;
    color: #6b7280;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.page-btn {
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border-radius: 20px;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #374151;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    transition: background .15s, color .15s, border-color .15s;
}

.page-btn:hover {
    background: #f3f4f6;
    color: #0b1533;
    text-decoration: none;
}

.page-btn.active {
    background: #0b1533;
    border-color: #0b1533;
    color: #fff;
}

.page-btn.disabled {
    opacity: .4;
    pointer-events: none;
    cursor: default;
}


    </style>
</head>

<body>
    <div class="admin-layout">

        @include('admin.layout.sidebar')

        <div class="admin-content" style="margin-left:260px;">

            <!-- Breadcrumb -->
            <div class="topbar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 7v5l3 3" />
                </svg>
                <span>{{ ucfirst(auth()->user()->role) }}</span>
                <span style="color:#d1d5db;">/</span>
                <strong>History Pesanan</strong>
            </div>

            <div style="padding:30px;">

                <div class="page-wrapper">

                    <!-- Header -->
                    <div class="page-header">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <div class="page-title">History Pesanan</div>
                                <div class="page-sub">Lihat riwayat semua pesanan (read-only)</div>
                            </div>

                            <form method="GET" action="{{ route('admin.orders.history') }}">
                                <div class="d-flex flex-wrap gap-2 align-items-end">

                                    <!-- Search -->
                                    <div>
                                        <label class="small text-muted mb-1 d-block">
                                            Cari Pesanan
                                        </label>
                                        <input type="text"
                                            name="search"
                                            class="search-box"
                                            value="{{ request('search') }}"
                                            placeholder="Cari ID order, nama, atau meja...">
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label class="small text-muted mb-1 d-block">
                                            Status
                                        </label>
                                        <select name="status"
                                            class="form-select"
                                            style="width:170px;border-radius:20px;font-size:13px;">
                                            <option value="">Semua Status</option>
                                            <option value="selesai"
                                                {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                                Selesai
                                            </option>
                                            <option value="dibatalkan"
                                                {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                                Dibatalkan
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Pembayaran -->
                                    <div>
                                        <label class="small text-muted mb-1 d-block">
                                            Pembayaran
                                        </label>
                                        <select name="payment"
                                            class="form-select"
                                            style="width:170px;border-radius:20px;font-size:13px;">
                                            <option value="">Semua Pembayaran</option>
                                            <option value="cash"
                                                {{ request('payment') == 'cash' ? 'selected' : '' }}>
                                                Cash
                                            </option>
                                            <option value="qris"
                                                {{ request('payment') == 'qris' ? 'selected' : '' }}>
                                                QRIS
                                            </option>
                                            <option value="transfer"
                                                {{ request('payment') == 'transfer' ? 'selected' : '' }}>
                                                Transfer
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Dari Tanggal -->
                                    <div>
                                        <label class="small text-muted mb-1 d-block">
                                            Dari Tanggal
                                        </label>
                                        <input type="date"
                                            name="start_date"
                                            class="form-control"
                                            style="width:170px;border-radius:20px;font-size:13px;"
                                            value="{{ request('start_date') }}">
                                    </div>

                                    <!-- Sampai Tanggal -->
                                    <div>
                                        <label class="small text-muted mb-1 d-block">
                                            Sampai Tanggal
                                        </label>
                                        <input type="date"
                                            name="end_date"
                                            class="form-control"
                                            style="width:170px;border-radius:20px;font-size:13px;"
                                            value="{{ request('end_date') }}">
                                    </div>

                                    <!-- Tombol -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-dark">
                                            Filter
                                        </button>

                                        <a href="{{ route('admin.orders.history') }}"
                                            class="btn btn-outline-secondary">
                                            Reset
                                        </a>
                                    </div>

                                </div>

                            </form>

                            <!-- Summary bar -->
<div class="summary-bar">
    <span>
        Total:
        <strong>{{ $totalCount }} pesanan</strong>
    </span>

    <span>
        Selesai:
        <strong>{{ $selesaiCount }}</strong>
    </span>

    <span>
        Dibatalkan:
        <strong>{{ $dibatalkanCount }}</strong>
    </span>

    @if(request('search'))
    <span style="color:#6b7280;">
        Hasil pencarian:
        "<strong>{{ request('search') }}</strong>"
    </span>
    @endif

    @if(request('status'))
    <span>
        Status:
        <strong>{{ ucfirst(request('status')) }}</strong>
    </span>
    @endif

    @if(request('payment'))
    <span>
        Pembayaran:
        <strong>{{ strtoupper(request('payment')) }}</strong>
    </span>
    @endif

    @if(request('start_date') || request('end_date'))
    <span>
        Periode:
        <strong>
            {{ request('start_date') ?: '-' }}
            s/d
            {{ request('end_date') ?: '-' }}
        </strong>
    </span>
    @endif
</div>

<!-- Pagination (di atas, biar gak perlu scroll) -->
@if($orders->hasPages())
<div class="pagination-bar">
    
    <div class="pagination-controls">
        @if ($orders->onFirstPage())
            <span class="page-btn disabled">&laquo; Prev</span>
        @else
            <a href="{{ $orders->previousPageUrl() }}" class="page-btn">&laquo; Prev</a>
        @endif

        @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
            @if ($page == $orders->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
        @endforeach

        @if ($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}" class="page-btn">Next &raquo;</a>
        @else
            <span class="page-btn disabled">Next &raquo;</span>
        @endif
    </div>
</div>
@endif


                            <!-- Table -->
                            @if($orders->isEmpty())
                            <div class="empty-state">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3 3" />
                                </svg>
                                <p>Belum ada riwayat pesanan</p>
                                <small>Pesanan yang selesai atau dibatalkan akan muncul di sini.</small>
                            </div>
                            @else
                            <div style="overflow-x:auto;">
                                <table class="history-table">
                                    <thead>
                                        <tr>
                                            <th>ID Order</th>
                                            <th>Tanggal</th>
                                            <th>Customer</th>
                                            <th>Meja</th>
                                            <th>Pesanan</th>
                                            <th>Total</th>
                                            <th>Bayar</th>
                                            <th>Status</th>
                                            <th>Struk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        @php
                                        $payRec = $order->payments->sortByDesc('created_at')->first();
                                        $bayar = $payRec?->payment_method ?? $order->payment_method ?? '-';
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="order-code">{{ $order->order_code }}</span>
                                            </td>
                                            <td style="white-space:nowrap; color:#6b7280; font-size:12px;">
                                                {{ $order->created_at?->setTimezone('Asia/Jakarta')->format('d M Y') ?? '-' }}<br>
                                                <span style="color:#9ca3af;">{{ $order->created_at?->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                                            </td>
                                            <td style="font-weight:600; font-size:13px;">{{ $order->customer_name ?? '-' }}</td>
                                            <td>
                                                @if($order->table_id)
                                                <span class="meja-badge">{{ $order->cafeTable->table_number ?? $order->table_id }}</span>
                                                @else
                                                <span class="meja-badge" style="background:#fee2e2;color:#dc2626;">Meja dihapus</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="items-list">
                                                    @forelse($order->orderDetails as $detail)
                                                    <div class="item-entry">
                                                        {{ $detail->quantity }}x {{ $detail->menu->name ?? '-' }}
                                                    </div>
                                                    @empty
                                                    <span style="color:#9ca3af; font-size:12px;">-</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td>
                                                <span class="total-amt">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                            </td>
                                            <td style="font-weight:600; font-size:12px;">
                                                {{ $bayar !== '-' ? strtoupper($bayar) : '-' }}
                                            </td>
                                            <td>
                                                <span class="status-badge badge-{{ $order->status }}">
                                                    {{ $order->status === 'selesai' ? 'Selesai' : 'Dibatalkan' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.struk', $order->order_id) }}"
                                                    target="_blank" class="btn-struk">

                                                    <img src="{{ asset('images/icons/receipt.png') }}"
                                                        alt="Struk"
                                                        width="20">


                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                        </div><!-- /page-wrapper -->

                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Auto-submit search on Enter
                document.querySelector('.search-box')?.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') this.closest('form').submit();
                });
            </script>
</body>

</html>