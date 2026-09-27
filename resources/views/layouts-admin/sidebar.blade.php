<!-- Main Sidebar Container -->
@php 
    $web_setting = \App\Models\WebSetting::first(); 
    $currentPath = request()->path();
@endphp

<style>
    /* Luxury Soft-Structuralism Admin Sidebar */
    .custom-admin-sidebar {
        background-color: #0b1e2c !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 4px 0 25px rgba(0, 0, 0, 0.25) !important;
    }

    .custom-admin-sidebar .brand-link {
        height: 57px !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 1rem !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        background: transparent !important;
        gap: 0.75rem !important;
        text-decoration: none !important;
    }

    .custom-admin-sidebar .sidebar-user-card {
        margin: 0.85rem 0.65rem 0.65rem !important;
        padding: 0.75rem 0.85rem !important;
        background: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 14px !important;
    }

    .custom-admin-sidebar .sidebar {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        padding-top: 0.25rem !important;
        padding-bottom: 1.5rem !important;
    }

    .custom-admin-sidebar .nav-sidebar {
        padding: 0 !important;
    }

    .custom-admin-sidebar .nav-header {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        font-size: 0.66rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
        color: #64748b !important;
        padding: 0.85rem 0.65rem 0.35rem !important;
        background: transparent !important;
    }

    .custom-admin-sidebar .nav-sidebar .nav-item {
        margin: 2px 0 !important;
    }

    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link {
        display: flex !important;
        align-items: center !important;
        padding: 0.55rem 0.75rem !important;
        border-radius: 11px !important;
        border: 1px solid transparent !important;
        color: #94a3b8 !important;
        background: transparent !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        font-size: 0.825rem !important;
        font-weight: 500 !important;
    }

    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link .nav-icon {
        width: 22px !important;
        text-align: center !important;
        margin-right: 0.65rem !important;
        margin-left: 0 !important;
        font-size: 0.95rem !important;
        color: #64748b !important;
        transition: color 0.2s ease !important;
        flex-shrink: 0 !important;
    }

    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link p {
        margin: 0 !important;
        flex: 1 1 auto !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link:hover {
        background: rgba(255, 255, 255, 0.06) !important;
        color: #ffffff !important;
    }

    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link:hover .nav-icon {
        color: #cbd5e1 !important;
    }

    /* Active nav item */
    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link.is-active,
    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link.active {
        background: linear-gradient(135deg, rgba(40, 114, 161, 0.45) 0%, rgba(30, 94, 134, 0.25) 100%) !important;
        border: 1px solid rgba(56, 189, 248, 0.4) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15) !important;
    }

    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link.is-active .nav-icon,
    .custom-admin-sidebar .nav-sidebar .nav-item .nav-link.active .nav-icon {
        color: #38bdf8 !important;
    }

    /* Live Proyektor special button */
    .custom-admin-sidebar .nav-link-live {
        background: rgba(16, 185, 129, 0.12) !important;
        border: 1px solid rgba(16, 185, 129, 0.25) !important;
        color: #34d399 !important;
        font-weight: 600 !important;
    }
    .custom-admin-sidebar .nav-link-live:hover {
        background: rgba(16, 185, 129, 0.2) !important;
        color: #6ee7b7 !important;
    }
    .custom-admin-sidebar .nav-link-live .nav-icon {
        color: #10b981 !important;
    }

    /* Logout item */
    .custom-admin-sidebar .nav-link-logout {
        color: #f87171 !important;
        font-weight: 600 !important;
    }
    .custom-admin-sidebar .nav-link-logout:hover {
        background: rgba(239, 68, 68, 0.12) !important;
        color: #fca5a5 !important;
    }
    .custom-admin-sidebar .nav-link-logout .nav-icon {
        color: #ef4444 !important;
    }
</style>

<aside class="main-sidebar custom-admin-sidebar">
    <!-- Brand Logo Header -->
    <a href="/admin" class="brand-link">
        <div class="rounded-xl overflow-hidden d-flex align-items-center justify-content-center shrink-0" 
             style="width: 34px; height: 34px; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 10px;">
            <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" 
                 alt="Logo" 
                 style="width: 24px; height: 24px; object-fit: contain;">
        </div>
        <div class="d-flex flex-column" style="line-height: 1.15; overflow: hidden;">
            <span class="font-display font-bold text-white text-sm tracking-tight truncate" style="max-width: 140px;">
                {{ $web_setting->web_title ?? 'PEMILOS' }}
            </span>
            <span style="font-size: 0.65rem; font-weight: 700; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.06em;">
                Admin Panel
            </span>
        </div>
    </a>

    <!-- Sidebar Scrollable Container -->
    <div class="sidebar">
        <!-- Admin Profile Card -->
        <div class="sidebar-user-card">
            <div class="d-flex align-items-center" style="gap: 0.75rem;">
                <div class="position-relative" style="width: 36px; height: 36px; flex-shrink: 0;">
                    <div class="rounded-xl d-flex align-items-center justify-content-center text-white font-display font-bold text-sm" 
                         style="width: 36px; height: 36px; border-radius: 11px; background: linear-gradient(135deg, #2872A1 0%, #1e5e86 100%); box-shadow: 0 4px 10px rgba(40, 114, 161, 0.35);">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="position-absolute rounded-full" 
                          style="bottom: -2px; right: -2px; width: 10px; height: 10px; background-color: #10b981; border: 2px solid #0b1e2c; border-radius: 50%;"></span>
                </div>
                <div class="d-flex flex-column overflow-hidden" style="line-height: 1.2;">
                    <span class="font-display font-bold text-white text-xs truncate">
                        {{ auth()->user()->name ?? 'ADMIN' }}
                    </span>
                    <span style="font-size: 0.7rem; font-weight: 600; color: #38bdf8; margin-top: 1px;">
                        Super Admin
                    </span>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @auth
                @if(auth()->user()->role == 1)
                
                <!-- Section: Navigasi Utama -->
                <li class="nav-header">NAVIGASI UTAMA</li>

                <!-- Dashboard -->
                @php $isDashboard = request()->is('admin') || request()->is('admin/dashboard*'); @endphp
                <li class="nav-item">
                    <a href="/admin" class="nav-link {{ $isDashboard ? 'is-active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Live Proyektor -->
                <li class="nav-item">
                    <a href="/live-count" target="_blank" class="nav-link nav-link-live">
                        <i class="nav-icon fas fa-broadcast-tower"></i>
                        <p>Live Proyektor</p>
                        <span class="badge" style="background: #10b981; color: #ffffff; border-radius: 999px; font-size: 0.6rem; font-weight: 800; padding: 0.2rem 0.45rem;">LIVE</span>
                    </a>
                </li>

                <!-- Section: Manajemen Data -->
                <li class="nav-header">MANAJEMEN DATA</li>

                <!-- Data Admin -->
                @php $isAdminList = request()->is('admin/admin-list*'); @endphp
                <li class="nav-item">
                    <a href="/admin/admin-list" class="nav-link {{ $isAdminList ? 'is-active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Data Admin</p>
                    </a>
                </li>

                <!-- Data Pemilih (DPT) -->
                @php $isUser = request()->is('admin/user*'); @endphp
                <li class="nav-item">
                    <a href="/admin/user" class="nav-link {{ $isUser ? 'is-active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pemilih (DPT)</p>
                    </a>
                </li>

                <!-- Data Paslon -->
                @php $isCandidate = request()->is('admin/osis-candidate*'); @endphp
                <li class="nav-item">
                    <a href="/admin/osis-candidate" class="nav-link {{ $isCandidate ? 'is-active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Data Paslon</p>
                    </a>
                </li>

                <!-- Section: Konfigurasi -->
                <li class="nav-header">KONFIGURASI</li>

                <!-- Pengaturan Web -->
                @php $isSetting = request()->is('admin/web-setting*'); @endphp
                <li class="nav-item">
                    <a href="/admin/web-setting" class="nav-link {{ $isSetting ? 'is-active' : '' }}">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>Pengaturan Web</p>
                    </a>
                </li>

                <!-- Keluar Akun -->
                <li class="nav-item mt-3 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                    <a href="/admin/logout" class="nav-link nav-link-logout">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Keluar Akun</p>
                    </a>
                </li>
                @endif
                @endauth
            </ul>
        </nav>
    </div>
</aside>