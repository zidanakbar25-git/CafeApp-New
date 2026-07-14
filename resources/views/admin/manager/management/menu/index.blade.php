{{-- resources/views/admin/menu/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu — Cozy Cafe</title>
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

        .menu-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .menu-table thead th {
            background: #f9fafb;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: #6b7280;
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .menu-table tbody tr {
            transition: background .15s;
        }

        .menu-table tbody tr:hover {
            background: #f9fafb;
        }

        .menu-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            font-size: 13px;
        }

        .menu-table tbody tr:last-child td {
            border-bottom: none;
        }

        .menu-name {
            font-weight: 700;
            color: #0b1533;
        }

        .badge-type {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .bg-food {
            background-color: #fef3c7;
            color: #92400e;
        }

        .bg-drink {
            background-color: #dbeafe;
            color: #1e40af;
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
            color: #4b5563;
        }

        .action-btn:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #111827;
        }

        .action-btn.danger-btn:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
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
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-tambah:hover {
            background: #1e2f5e;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .search-wrapper {
            position: relative;
            width: 240px;
        }

        .search-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .search-input {
            width: 100%;
            padding: 8px 16px 8px 38px;
            font-size: 13px;
            border: 1px solid #e5e7eb;
            border-radius: 30px;
            outline: none;
            transition: all 0.2s;
            color: #374151;
        }

        .search-input:focus {
            border-color: #0b1533;
            box-shadow: 0 0 0 3px rgba(11, 21, 51, 0.08);
        }

        /* ── Modal Hapus Custom ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(11, 21, 51, 0.35);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.22s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        .modal-box {
            background: #fff;
            border-radius: 28px;
            padding: 36px 32px 28px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 24px 60px rgba(11, 21, 51, 0.14);
            transform: translateY(16px) scale(0.97);
            transition: transform 0.24s ease, opacity 0.22s ease;
            opacity: 0;
            text-align: center;
        }

        .modal-overlay.show .modal-box {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .modal-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: #fff1f2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }

        .modal-icon-wrap i {
            font-size: 26px;
            color: #e11d48;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #0b1533;
            margin-bottom: 8px;
        }

        .modal-desc {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 6px;
        }

        .modal-menu-name {
            font-size: 14px;
            font-weight: 700;
            color: #0b1533;
            background: #f5f7fb;
            border-radius: 10px;
            padding: 8px 16px;
            display: inline-block;
            margin: 8px 0 22px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .btn-modal-cancel {
            flex: 1;
            padding: 11px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-modal-cancel:hover {
            background: #f3f4f6;
        }

        .btn-modal-hapus {
            flex: 1;
            padding: 11px;
            border-radius: 14px;
            border: none;
            background: #e11d48;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        .btn-modal-hapus:hover {
            background: #be123c;
        }

        .toggle-switch {
            width: 44px;
            height: 24px;
            border-radius: 30px;
            border: none;
            background: #e5e7eb;
            cursor: pointer;
            position: relative;
            transition: background 0.25s ease;
            padding: 0;
            display: inline-flex;
            align-items: center;
        }

        .toggle-switch.on {
            background: #16a34a;
        }

        .toggle-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            position: absolute;
            left: 3px;
            transition: left 0.25s ease;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        }

        .toggle-switch.on .toggle-thumb {
            left: 23px;
        }
    </style>
</head>

<body>

    <div class="admin-layout">
        @include('admin.layout.sidebar')

        <div class="admin-content">
            <div class="topbar">
                <i class="fa-solid fa-utensils text-secondary"></i>
                <span>Manager</span>
                <span style="color:#d1d5db;">/</span>
                <strong>Manajemen Menu</strong>
            </div>

            <div style="padding: 0 30px 10px; margin-top: 28px;">
                <div style="font-size:32px;font-weight:700;color:#0b1533;">Manajemen Menu</div>
                <div style="color:#6b7280;">Kelola menu makanan dan minuman dan kelola status ketersediaannya.</div>
            </div>

            @if(session('success'))
            <div style="margin: 0 30px;" class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="fa-solid fa-circle-check text-success"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card-wrap" id="menu-card-container">
                <div class="card-top">
                    <div>
                        <div class="card-title">Daftar Menu Aktif</div>
                        <div class="card-sub" id="menu-count-badge">{{ $menus->count() }} menu terdaftar</div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="search-wrapper">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="ajax-search-input" class="search-input"
                                placeholder="Cari nama menu..." value="{{ request('search') }}" autocomplete="off">
                        </div>
                        <a href="{{ route('admin.menu.create') }}" class="btn-tambah">
                            <i class="fa-solid fa-plus"></i> Tambah Menu
                        </a>
                    </div>
                </div>

                <div id="table-responsive-container">
                    @if($menus->isEmpty())
                    <div class="empty-state">
                        <i class="fa-regular fa-folder-open mb-2 fs-2"></i>
                        <p style="font-size:15px;font-weight:600;color:#6b7280;margin-top:12px;">Belum ada menu terdaftar.</p>
                    </div>
                    @else
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th style="width:100px;">Tipe</th>
                                <th>Kategori</th>
                                <th style="text-align:right; width:130px;">Harga</th>
                                <th style="width:100px; text-align:center;">Status</th>
                                <th style="text-align:center; width:120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="menu-table-body">
                            @foreach($menus as $menu)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $menu->image_url) }}"
                                            onerror="this.src='{{ asset($menu->image_url) }}'; this.onerror=null;"
                                            class="rounded shadow-sm" style="width:42px; height:42px; object-fit: cover;" alt="">
                                        <span class="menu-name">{{ $menu->name }}</span>
                                    </div>
                                </td>
                                <td class="text-secondary text-truncate" style="max-width: 200px;">{{ $menu->description ?? '-' }}</td>
                                <td>
                                    <span class="badge-type {{ $menu->category_type === 'Drink' ? 'bg-drink' : 'bg-food' }}">
                                        {{ $menu->category_type }}
                                    </span>
                                </td>

                                {{-- ── kategori --}}
                                <td class="text-muted">{{ $menu->sub_name }}</td>

                                {{-- ── harga --}}
                                <td style="text-align:right;" class="fw-bold text-dark">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>

                                {{-- ── toggle --}}
                                <td style="text-align:center;">
                                    <form action="{{ route('admin.menu.toggleActive', $menu->menu_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="toggle-switch {{ $menu->is_active ? 'on' : '' }}"
                                            title="{{ $menu->is_active ? 'Nonaktifkan' : 'Aktifkan' }} menu"
                                            onclick="event.preventDefault(); Swal.fire({title: '{{ $menu->is_active ? 'Nonaktifkan' : 'Aktifkan' }} menu {{ addslashes($menu->name) }}?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#451a03', confirmButtonText: 'Ya', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                            <span class="toggle-thumb"></span>
                                        </button>
                                    </form>
                                </td>

                                {{-- ── aksi edit & hapus --}}
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.menu.edit', $menu->menu_id) }}" class="action-btn" title="Edit Menu">
                                            <i class="fa-solid fa-pencil text-xs"></i>
                                        </a>

                                        
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
    </div>

    {{-- ── Modal Konfirmasi Hapus ── --}}
    <div class="modal-overlay" id="deleteModal" onclick="handleOverlayClick(event)">
        <div class="modal-box">
            <div class="modal-icon-wrap">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="modal-title">Hapus Menu?</div>
            <div class="modal-desc">Anda akan menghapus menu</div>
            <div class="modal-menu-name" id="modal-menu-name">—</div>
            <div class="modal-desc" style="margin-bottom: 0;">Tindakan ini tidak dapat dibatalkan.</div>
            <div class="modal-actions" style="margin-top: 24px;">
                <button class="btn-modal-cancel" onclick="closeDeleteModal()">Batal</button>
                <button class="btn-modal-hapus" id="btn-confirm-hapus" onclick="submitDelete()">
                    <i class="fa-solid fa-trash-can" style="font-size:13px;"></i>
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Modal Hapus ──
        let activeDeleteId = null;

        function showDeleteModal(id, name) {
            activeDeleteId = id;
            document.getElementById('modal-menu-name').textContent = name;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            activeDeleteId = null;
        }

        function submitDelete() {
            if (!activeDeleteId) return;
            document.getElementById('delete-form-' + activeDeleteId).submit();
        }

        // Klik di luar modal-box → tutup
        function handleOverlayClick(e) {
            if (e.target === document.getElementById('deleteModal')) {
                closeDeleteModal();
            }
        }

        // Tekan Escape → tutup
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });

        // ── AJAX Live Search ──
        document.getElementById('ajax-search-input').addEventListener('input', function(e) {
            let keyword = e.target.value;

            fetch(`{{ route('admin.menu.index') }}?search=${encodeURIComponent(keyword)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('menu-count-badge').innerText = `${data.count} menu terdaftar`;

                    let container = document.getElementById('table-responsive-container');

                    if (data.html.trim() === '') {
                        container.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-regular fa-folder-open mb-2 fs-2"></i>
                        <p style="font-size:15px;font-weight:600;color:#6b7280;margin-top:12px;">Menu yang Anda cari tidak ditemukan.</p>
                    </div>`;
                    } else {
                        container.innerHTML = `
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th style="width:100px;">Tipe</th>
                                <th>Kategori</th>
                                <th style="text-align:right; width:130px;">Harga</th>
                                <th style="text-align:center; width:120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="menu-table-body">
                            ${data.html}
                        </tbody>
                    </table>`;
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
        });
    </script>
</body>

</html>