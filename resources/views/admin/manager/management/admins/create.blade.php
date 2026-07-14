<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Admin — Cozy Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin:0; background:#f5f7fb; font-family:'Segoe UI',system-ui,sans-serif; }
        .admin-layout { display:flex; }
        .admin-content { flex:1; padding:0; margin-left:260px; }
        .topbar {
            display:flex; align-items:center; gap:10px; padding:14px 30px;
            border-bottom:1px solid #e5e7eb; background:#fff; font-size:13px; color:#6b7280;
        }
        .topbar strong { color:#111827; }
        .form-card {
            background:#fff; border-radius:24px; border:1px solid #e5e7eb;
            padding:28px 32px; margin:28px 30px; max-width:620px;
        }
        .form-label { font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
        .form-control, .form-select {
            border:1px solid #e5e7eb; border-radius:12px; padding:10px 14px;
            font-size:13px; background:#f9fafb; transition:border-color .15s, background .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color:#0b1533; background:#fff; box-shadow:none;
        }
        .form-control.is-invalid, .form-select.is-invalid { border-color:#ef4444; }
        .invalid-feedback { font-size:12px; color:#dc2626; }
        .btn-simpan {
            background:#0b1533; color:#fff; border:none; border-radius:30px;
            padding:10px 24px; font-size:13px; font-weight:600; cursor:pointer;
            transition:background .2s;
        }
        .btn-simpan:hover { background:#1e2f5e; }
        .btn-batal {
            background:#f3f4f6; color:#374151; border:none; border-radius:30px;
            padding:10px 24px; font-size:13px; font-weight:600; cursor:pointer;
            text-decoration:none; transition:background .2s;
        }
        .btn-batal:hover { background:#e5e7eb; color:#374151; }
        .divider { border:none; border-top:1px solid #f3f4f6; margin:20px 0; }
    </style>
</head>
<body>
<div class="admin-layout">

    @include('admin.layout.sidebar')

    <div class="admin-content">

        <div class="topbar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            <span>Manager</span>
            <span style="color:#d1d5db;">/</span>
            <a href="{{ route('admin.admins.index') }}" style="color:#6b7280;text-decoration:none;">Manajemen Admin</a>
            <span style="color:#d1d5db;">/</span>
            <strong>Tambah Admin</strong>
        </div>

        <div style="padding:0 30px 10px; margin-top:28px;">
            <div style="font-size:32px;font-weight:700;color:#0b1533;">Tambah Admin / Kasir</div>
            <div style="color:#6b7280;">Buat akun baru untuk admin atau kasir.</div>
        </div>

        <div class="form-card">

            @if($errors->any())
            <div class="alert alert-danger" style="border-radius:12px; font-size:13px;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.admins.store') }}">
                @csrf

                

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}" placeholder="Contoh: budi123" required>
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                

               
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 6 karakter" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <hr class="divider">

                {{-- Role otomatis Kasir, tidak perlu dipilih --}}
                <input type="hidden" name="role" value="kasir">

                <hr class="divider">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-simpan">Simpan</button>
                    <a href="{{ route('admin.admins.index') }}" class="btn-batal">Batal</a>
                </div>
            </form>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
