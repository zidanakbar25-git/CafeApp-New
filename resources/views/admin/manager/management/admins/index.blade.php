<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Admin — Cozy Cafe</title>
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
        }

        .btn-tambah:hover {
            background: #1e2f5e;
            color: #fff;
        }

        .admin-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .admin-table thead th {
            background: #f9fafb;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: #6b7280;
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .admin-table tbody tr {
            transition: background .15s;
        }

        .admin-table tbody tr:hover {
            background: #f9fafb;
        }

        .admin-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        .action-btn {
            width: 34px;
            height: 34px;
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
            font-size: 15px;
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

        .action-btn:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        .badge-role-super {
            background: #0b1533;
            color: #fff;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-role-kasir {
            background: #e5e7eb;
            color: #374151;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-aktif {
            background: #dcfce7;
            color: #16a34a;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-nonaktif {
            background: #fee2e2;
            color: #dc2626;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Modal password result */
        .password-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin: 12px 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #0b1533;
            font-family: 'Courier New', monospace;
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

        .toast-msg.danger {
            background: #dc2626;
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
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
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <span>Manager</span>
                <span style="color:#d1d5db;">/</span>
                <strong>Manajemen Admin</strong>
            </div>

            <div style="padding:0 30px 10px; margin-top:28px;">
                <div style="font-size:32px;font-weight:700;color:#0b1533;">Manajemen Admin</div>
                <div style="color:#6b7280;">Kelola akun admin dan kasir.</div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
            <div style="margin:12px 30px;" class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div style="margin:12px 30px;" class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2">
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
                        <div class="card-title">Daftar Akun Kasir</div>
                        <div class="card-sub">{{ $admins->count() }} akun terdaftar</div>
                    </div>
                    @if(auth()->user()->role === 'manager')
                    <a href="{{ route('admin.admins.create') }}" class="btn-tambah">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Tambah Kasir
                    </a>
                    @endif
                </div>

                @if($admins->isEmpty())
                <div class="empty-state">
                    <p style="font-size:15px;font-weight:600;color:#6b7280;">Belum ada kasir terdaftar.</p>
                </div>
                @else
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th style="text-align:right; padding-right:28px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td style="color:#9ca3af; font-size:13px;">#{{ $admin->admin_id }}</td>
                            <td><code style="font-size:12px; color:#6b7280;">{{ $admin->username }}</code></td>
                            <td><span class="badge-role-kasir">Kasir</span></td>
                    
                            <td style="text-align:right; padding-right:24px;">
                                <div class="d-flex gap-2 justify-content-end">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.admins.edit', $admin->admin_id) }}"
                                        class="action-btn" title="Edit">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>

                                    {{-- Hapus (tidak bisa hapus diri sendiri) --}}
                                    @if(auth()->user()->admin_id != $admin->admin_id)
                                    <button class="action-btn danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="{{ $admin->admin_id }}"
                                        data-name="{{ $admin->name ?? $admin->username }}">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                                    @else
                                    <button class="action-btn" disabled title="Tidak bisa hapus akun sendiri">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        </svg>
                                    </button>
                                    @endif
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

    {{-- Modal Hapus --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:20px; border:none;">
                <div class="modal-body p-4 text-center">
                    <div style="font-size:40px; margin-bottom:12px;">⚠️</div>
                    <div style="font-size:16px; font-weight:700; color:#0b1533; margin-bottom:6px;">Hapus Admin?</div>
                    <div style="font-size:13px; color:#6b7280; margin-bottom:20px;">
                        Yakin ingin menghapus <strong id="deleteName"></strong>?<br>Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                        <form id="deleteForm" method="POST" class="flex-fill">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Password Baru (setelah reset) --}}
    @if(session('reset_success'))
    <div class="modal fade" id="resetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:20px; border:none;">
                <div class="modal-body p-4 text-center">
                    <div style="font-size:40px; margin-bottom:12px;">🔑</div>
                    <div style="font-size:16px; font-weight:700; color:#0b1533; margin-bottom:4px;">Password Direset!</div>
                    <div style="font-size:13px; color:#6b7280; margin-bottom:4px;">
                        Password baru untuk <strong>{{ session('reset_name') }}</strong>:
                    </div>
                    <div class="password-box">{{ session('new_password') }}</div>
                    <div style="font-size:12px; color:#dc2626; margin-bottom:16px;">Catat sekarang — tidak bisa ditampilkan lagi.</div>
                    <button class="btn-tambah w-100 justify-content-center" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Isi modal hapus
        document.getElementById('deleteModal')?.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('deleteName').textContent = btn.dataset.name;
            document.getElementById('deleteForm').action = `/admin/admins/${btn.dataset.id}`;
        });

        // Auto buka modal reset password
        @if(session('reset_success'))
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('resetModal')).show();
        });
        @endif
    </script>
</body>

</html>