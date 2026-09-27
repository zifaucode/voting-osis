<!-- Main Sidebar Container -->
@php 
    $web_setting = \App\Models\WebSetting::first(); 
    $currentRoute = request()->path();
@endphp

<aside class="main-sidebar sidebar-dark-primary" style="background: #0b1e2c; border-right: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 4px 0 25px rgba(0, 0, 0, 0.25);">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link d-flex align-items-center px-4 py-3" style="border-bottom: 1px solid rgba(255, 255, 255, 0.08); text-decoration: none; gap: 0.75rem;">
        <div class="rounded-xl overflow-hidden d-flex align-items-center justify-content-center shrink-0" 
             style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px;">
            <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" 
                 alt="Logo" 
                 style="width: 26px; height: 26px; object-fit: contain;">
        </div>
        <div class="d-flex flex-column" style="line-height: 1.2;">
            <span class="font-display font-bold text-white text-sm tracking-tight truncate" style="max-width: 160px;">
                {{ $web_setting->web_title ?? 'PEMILOS' }}
            </span>
            <span class="text-[10px] font-semibold text-ocean-300 uppercase tracking-widest">
                Admin Panel
            </span>
        </div>
    </a>

    <!-- Sidebar Content -->
    <div class="sidebar px-3 py-3">
        <!-- Sidebar User Card -->
        <div class="p-3 mb-4 rounded-2xl" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.06);">
            <div class="d-flex align-items-center" style="gap: 0.75rem;">
                <div class="relative">
                    <div class="rounded-xl bg-gradient-to-tr from-ocean-500 to-ocean-400 text-white font-display font-bold text-sm d-flex align-items-center justify-content-center" 
                         style="width: 38px; height: 38px; border-radius: 12px; box-shadow: 0 4px 12px rgba(40, 114, 161, 0.35);">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-[#0b1e2c]"></span>
                </div>
                <div class="d-flex flex-column truncate" style="line-height: 1.2;">
                    <span class="font-display font-semibold text-white text-xs truncate">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </span>
                    <span class="text-[11px] text-slate-400 mt-0.5">
                        Super Admin
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation Label -->
        <div class="px-2 mb-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigasi Utama</span>
        </div>

        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="gap: 0.35rem;">
                @auth
                @if(auth()->user()->role == 1)
                
                <!-- Dashboard -->
                @php $isDashboard = request()->is('admin') || request()->is('admin/dashboard*'); @endphp
                <li class="nav-item">
                    <a href="/admin" class="nav-link d-flex align-items-center transition-all" 
                       style="{{ $isDashboard ? 'background: rgba(40, 114, 161, 0.28); color: #ffffff; border: 1px solid rgba(40, 114, 161, 0.5); font-weight: 600; box-shadow: 0 4px 16px rgba(40, 114, 161, 0.25);' : 'color: #94a3b8; border: 1px solid transparent;' }} border-radius: 14px; padding: 0.65rem 0.9rem; gap: 0.75rem;">
                        <i class="nav-icon fas fa-th-large {{ $isDashboard ? 'text-ocean-400' : 'text-slate-400' }}" style="font-size: 1rem; width: 20px;"></i>
                        <p class="mb-0 font-display text-xs">Dashboard</p>
                    </a>
                </li>

                <!-- Live Count Proyektor -->
                <li class="nav-item">
                    <a href="/live-count" target="_blank" class="nav-link d-flex align-items-center justify-content-between transition-all" 
                       style="background: rgba(16, 185, 129, 0.12); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 14px; padding: 0.65rem 0.9rem; margin: 0.25rem 0;">
                        <div class="d-flex align-items-center" style="gap: 0.75rem;">
                            <i class="nav-icon fas fa-broadcast-tower text-emerald-400" style="font-size: 1rem; width: 20px;"></i>
                            <p class="mb-0 font-display text-xs font-semibold">Live Proyektor</p>
                        </div>
                        <span class="badge px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider" 
                              style="background: #10b981; color: #ffffff; border-radius: 999px;">
                            LIVE
                        </span>
                    </a>
                </li>

                <!-- Master Data Label -->
                <div class="px-2 mt-3 mb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manajemen Data</span>
                </div>

                <!-- Data Admin -->
                @php $isAdminList = request()->is('admin/admin-list*'); @endphp
                <li class="nav-item">
                    <a href="/admin/admin-list" class="nav-link d-flex align-items-center transition-all" 
                       style="{{ $isAdminList ? 'background: rgba(40, 114, 161, 0.28); color: #ffffff; border: 1px solid rgba(40, 114, 161, 0.5); font-weight: 600;' : 'color: #94a3b8; border: 1px solid transparent;' }} border-radius: 14px; padding: 0.65rem 0.9rem; gap: 0.75rem;">
                        <i class="nav-icon fas fa-user-shield {{ $isAdminList ? 'text-ocean-400' : 'text-slate-400' }}" style="font-size: 1rem; width: 20px;"></i>
                        <p class="mb-0 font-display text-xs">Data Admin</p>
                    </a>
                </li>

                <!-- Data User / DPT -->
                @php $isUser = request()->is('admin/user*'); @endphp
                <li class="nav-item">
                    <a href="/admin/user" class="nav-link d-flex align-items-center transition-all" 
                       style="{{ $isUser ? 'background: rgba(40, 114, 161, 0.28); color: #ffffff; border: 1px solid rgba(40, 114, 161, 0.5); font-weight: 600;' : 'color: #94a3b8; border: 1px solid transparent;' }} border-radius: 14px; padding: 0.65rem 0.9rem; gap: 0.75rem;">
                        <i class="nav-icon fas fa-users {{ $isUser ? 'text-ocean-400' : 'text-slate-400' }}" style="font-size: 1rem; width: 20px;"></i>
                        <p class="mb-0 font-display text-xs">Data Pemilih (DPT)</p>
                    </a>
                </li>

                <!-- Data Kandidat -->
                @php $isCandidate = request()->is('admin/osis-candidate*'); @endphp
                <li class="nav-item">
                    <a href="/admin/osis-candidate" class="nav-link d-flex align-items-center transition-all" 
                       style="{{ $isCandidate ? 'background: rgba(40, 114, 161, 0.28); color: #ffffff; border: 1px solid rgba(40, 114, 161, 0.5); font-weight: 600;' : 'color: #94a3b8; border: 1px solid transparent;' }} border-radius: 14px; padding: 0.65rem 0.9rem; gap: 0.75rem;">
                        <i class="nav-icon fas fa-id-card {{ $isCandidate ? 'text-ocean-400' : 'text-slate-400' }}" style="font-size: 1rem; width: 20px;"></i>
                        <p class="mb-0 font-display text-xs">Data Paslon</p>
                    </a>
                </li>

                <!-- Setting -->
                <div class="px-2 mt-3 mb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Konfigurasi</span>
                </div>

                @php $isSetting = request()->is('admin/web-setting*'); @endphp
                <li class="nav-item">
                    <a href="/admin/web-setting" class="nav-link d-flex align-items-center transition-all" 
                       style="{{ $isSetting ? 'background: rgba(40, 114, 161, 0.28); color: #ffffff; border: 1px solid rgba(40, 114, 161, 0.5); font-weight: 600;' : 'color: #94a3b8; border: 1px solid transparent;' }} border-radius: 14px; padding: 0.65rem 0.9rem; gap: 0.75rem;">
                        <i class="nav-icon fas fa-sliders-h {{ $isSetting ? 'text-ocean-400' : 'text-slate-400' }}" style="font-size: 1rem; width: 20px;"></i>
                        <p class="mb-0 font-display text-xs">Pengaturan Web</p>
                    </a>
                </li>

                <!-- Keluar -->
                <li class="nav-item mt-4 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                    <a href="/admin/logout" class="nav-link d-flex align-items-center text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition-all" 
                       style="border-radius: 14px; padding: 0.65rem 0.9rem; gap: 0.75rem;">
                        <i class="nav-icon fas fa-power-off text-rose-400" style="font-size: 1rem; width: 20px;"></i>
                        <p class="mb-0 font-display text-xs font-semibold">Keluar Akun</p>
                    </a>
                </li>
                @endif
                @endauth
            </ul>
        </nav>
    </div>
</aside>