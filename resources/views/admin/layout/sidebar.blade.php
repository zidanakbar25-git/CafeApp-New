{{-- resources/views/dashboard/layout/sidebar.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'kasir';
    $roleLabel = match($role) {
        'manager' => 'Manager',
        'kasir'   => 'Kasir',
        default   => ucfirst($role),
    };
@endphp

<style>
    .sidebar-wrapper {
        width: 260px;
        background: #fff;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding: 16px;
        z-index: 1000;
    }

    .brand-section {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 8px;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 16px;
    }

    .brand-icon {
        width: 36px;
        height: 36px;
        background: #451a03;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
    }

    .brand-title {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }

    .brand-role {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
    }

    .menu-group-title {
        padding: 0 12px;
        font-size: 10px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
        margin-top: 16px;
    }

    .nav-link-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        color: #6b7280;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.15s;
    }

    .nav-link-custom:hover {
        color: #111827;
        background: #f9fafb;
    }

    .nav-link-custom.active {
        background: #f1f5f9;
        color: #0f172a;
        font-weight: 600;
    }

    .nav-link-custom i {
        font-size: 14px;
        width: 16px;
        text-align: center;
    }

    .btn-logout-sidebar {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: #ef4444;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 10px;
        transition: background 0.2s;
        border: none;
        width: 100%;
        cursor: pointer;
    }

    .btn-logout-sidebar:hover {
        background: #dc2626;
        color: #fff;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<aside class="sidebar-wrapper">
    <div>
        {{-- Brand --}}
        <div class="brand-section">
            <div class="brand-icon">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <div>
                <div class="brand-title">Cozy Cafe</div>
                {{-- Role ditampilkan dinamis --}}
                <div class="brand-role">{{ $roleLabel }}</div>
            </div>
        </div>

        {{-- Menu Umum: Dashboard (hanya untuk non-manager) --}}
        @if($role !== 'manager')
        <div>
            <div class="menu-group-title">Umum</div>
            <nav class="d-flex flex-column gap-1">
                <a href="{{ route('admin.dashboard') }}"
                class="nav-link-custom {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </nav>
        </div>
        @endif

        {{-- Menu Khusus Manager --}}
        @if($role === 'manager')
        <div>
            <div class="menu-group-title">Manajemen</div>
            <nav class="d-flex flex-column gap-1">

                <a href="{{ route('admin.tables.index') }}"
                   class="nav-link-custom {{ Request::routeIs('admin.tables.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-window-maximize"></i>
                    <span>Manajemen Meja</span>
                </a>

                <a href="{{ route('admin.menu.index') }}"
                   class="nav-link-custom {{ Request::routeIs('admin.menu.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-utensils"></i>
                    <span>Manajemen Menu</span>
                </a>

                <a href="{{ route('admin.admins.index') }}"
                   class="nav-link-custom {{ Request::routeIs('admin.admins.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-user"></i>
                    <span>Manajemen Admin</span>
                </a>

            </nav>
        </div>
        @endif

        {{-- Riwayat (semua role) --}}
        <div>
            <div class="menu-group-title">Riwayat</div>
            <nav class="d-flex flex-column gap-1">
                <a href="{{ route('admin.orders.history') }}"
                   class="nav-link-custom {{ Request::routeIs('admin.orders.history') ? 'active' : '' }}">
                    <i class="fa-regular fa-clock"></i>
                    <span>History Pesanan</span>
                </a>
            </nav>
        </div>

    </div>

    {{-- Logout --}}
    <div class="pt-3" style="border-top: 1px solid #f3f4f6;">
        <form id="sidebar-logout-form"
              action="{{ route('logout') }}"
              method="POST"
              onsubmit="event.preventDefault(); Swal.fire({title: 'Apakah Anda yakin ingin keluar?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, Keluar', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) { this.submit(); } })">
            @csrf
            <button type="submit" class="btn-logout-sidebar">
                <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>