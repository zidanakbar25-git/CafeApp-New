<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Meja — Cozy Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .qr-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .qr-table thead th {
            background: #f9fafb;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: #6b7280;
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .qr-table tbody tr {
            transition: background .15s;
        }

        .qr-table tbody tr:hover {
            background: #f9fafb;
        }

        .qr-table tbody td {
            padding: 18px 20px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .qr-table tbody tr:last-child td {
            border-bottom: none;
        }

        .meja-number {
            font-size: 22px;
            font-weight: 700;
            color: #0b1533;
        }

        .qr-url {
            color: #d97706;
            font-weight: 500;
            font-size: 13.5px;
            text-decoration: none;
        }

        .qr-url:hover {
            text-decoration: underline;
        }

        .qr-preview {
            width: 72px;
            height: 72px;
            padding: 6px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .qr-preview svg {
            width: 60px;
            height: 60px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s, border-color .15s;
            text-decoration: none;
            color: inherit;
        }

        .action-btn:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .action-btn.danger {
            border-color: #fca5a5;
        }

        .action-btn.danger:hover {
            background: #fee2e2;
        }

        .action-btn.warning {
            border-color: #fcd34d;
        }

        .action-btn.warning:hover {
            background: #fef9c3;
        }

        .search-box {
            border-radius: 30px;
            border: 1px solid #e5e7eb;
            padding: 9px 16px 9px 40px;
            font-size: 13px;
            outline: none;
            width: 260px;
            background: #f9fafb;
        }

        .search-box:focus {
            border-color: #0b1533;
            background: #fff;
        }

        .search-wrap {
            position: relative;
        }

        .search-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .card-wrap {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin: 28px 30px;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #f3f4f6;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #0b1533;
        }

        .card-sub {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .btn-tambah {
            background: #0b1533;
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background .2s;
        }

        .btn-tambah:hover {
            background: #1e2f5e;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast-msg {
            background: #0b1533;
            color: #fff;
            padding: 12px 20px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .15);
            animation: slideIn .3s ease;
        }

        .toast-msg.success {
            background: #16a34a;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <div id="toast-container"></div>

    <div class="admin-layout">

        @include('admin.layout.sidebar')

        <div class="admin-content">

            {{-- Topbar --}}
            <div class="topbar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <line x1="3" y1="9" x2="21" y2="9" />
                    <line x1="3" y1="15" x2="21" y2="15" />
                    <line x1="9" y1="3" x2="9" y2="21" />
                </svg>
                <span>Manager</span>
                <span style="color:#d1d5db;">/</span>
                <strong>Manajemen Meja</strong>
            </div>

            <div style="padding: 0 30px 10px; margin-top: 28px;">
                <div style="font-size:32px;font-weight:700;color:#0b1533;">Manajemen Meja</div>
                <div style="color:#6b7280;">Kelola meja dan QR Code untuk sistem pemesanan.</div>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div style="margin: 0 30px;"
                class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div style="margin: 0 30px;"
                class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Card Tabel --}}
            <div class="card-wrap">
                <div class="card-top">
                    <div>
                        <div class="card-title">Daftar QR Code Aktif</div>
                        <div class="card-sub">{{ $tables->count() }} meja terdaftar</div>
                    </div>
                    <div class="d-flex gap-3 align-items-center flex-wrap">
                        {{-- Search --}}
                        <form method="GET" action="{{ route('admin.tables.index') }}">
                            <div class="search-wrap">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"
                                    stroke-width="2">
                                    <circle cx="11" cy="11" r="7" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                                <input type="text" name="search" class="search-box" placeholder="Cari nomor meja..."
                                    value="{{ request('search') }}">
                            </div>
                        </form>
                        {{-- Tambah --}}
                        <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah Meja
                        </button>
                    </div>
                </div>

                {{-- Tabel --}}
                @if($tables->isEmpty())
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <line x1="3" y1="9" x2="21" y2="9" />
                        <line x1="9" y1="3" x2="9" y2="21" />
                    </svg>
                    <p style="font-size:15px;font-weight:600;color:#6b7280;margin-top:12px;">Belum ada meja terdaftar.
                    </p>
                </div>
                @else
                <table class="qr-table">
                    <thead>
                        <tr>
                            <th style="width:80px;">Meja</th>
                            <th>Link QR Code Target</th>
                            <th style="width:120px; text-align:center;">Preview</th>
                            <th style="width:140px; text-align:center; padding-right:28px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tables as $table)
                        <tr>
                            <td><span class="meja-number">{{ $table->table_number }}</span></td>
                            <td>
                                <a href="{{ $table->qr_url }}" target="_blank" class="qr-url">
                                    {{ $table->qr_url }}
                                </a>
                            </td>
                            <td style="text-align:center;">
                                <div class="qr-preview">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(60)->generate($table->qr_url) !!}
                                </div>
                            </td>
                            <td style="text-align:right; padding-right:24px;">
                                <div class="d-flex gap-2 justify-content-end">
                                    {{-- Cetak --}}
                                    <a href="{{ route('admin.tables.print', $table->table_id) }}"
                                        target="_blank" class="action-btn" title="Cetak QR Code">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2">
                                            <polyline points="6 9 6 2 18 2 18 9" />
                                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                            <rect x="6" y="14" width="12" height="8" />
                                        </svg>
                                    </a>

                                    

                                    {{-- Hapus Meja --}}
                                    <form action="{{ route('admin.tables.destroy', $table->table_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn danger" title="Hapus Meja"
                                            onclick="event.preventDefault(); Swal.fire({title: 'Hapus meja {{ $table->table_number }}?', text: 'Riwayat pesanan tetap tersimpan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal Tambah Meja --}}
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:20px; border:none;">
                <div class="modal-body p-4">
                    <h6 style="font-weight:700;color:#0b1533;margin-bottom:16px;">Tambah Meja Baru</h6>
                    <form action="{{ route('admin.tables.store') }}" method="POST">
                        @csrf
                        <div style="margin-bottom:16px;">
                            <label style="font-size:12px;color:#6b7280;margin-bottom:6px;display:block;">Nomor Meja</label>
                            <input type="number" name="table_number"
                                class="form-control @error('table_number') is-invalid @enderror"
                                placeholder="Contoh: 1" min="1" autofocus
                                style="border-radius:10px;">
                            @error('table_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn-tambah flex-fill justify-content-center">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Buka modal otomatis jika ada error validasi --}}
    @if($errors->has('table_number'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        });
    </script>
    @endif

</body>

</html>