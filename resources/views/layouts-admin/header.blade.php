<!-- Modern Glass Top Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light border-b border-slate-200/80 px-3 sm:px-6 py-2.5 transition-all duration-300" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid rgba(226, 232, 240, 0.85);">
    <!-- Left navbar links -->
    <ul class="navbar-nav d-flex align-items-center" style="gap: 0.75rem;">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center text-slate-700 hover:text-ocean-600 transition-all" 
               data-widget="pushmenu" 
               href="#" 
               role="button"
               style="width: 38px; height: 38px; border-radius: 12px; background: rgba(241, 245, 249, 0.9); border: 1px solid rgba(226, 232, 240, 0.8);">
                <i class="fas fa-bars" style="font-size: 0.95rem;"></i>
            </a>
        </li>
        <li class="nav-item d-none d-md-flex align-items-center">
            <div class="d-flex align-items-center px-3 py-1.5 rounded-full" style="background: rgba(40, 114, 161, 0.06); border: 1px solid rgba(40, 114, 161, 0.15); gap: 0.5rem;">
                <span class="d-inline-block rounded-full bg-emerald-500 animate-pulse" style="width: 7px; height: 7px; box-shadow: 0 0 8px rgba(16, 185, 129, 0.8);"></span>
                <span class="font-display text-xs font-semibold text-ocean-950 tracking-wide">
                    Console Administrator
                </span>
            </div>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto d-flex align-items-center" style="gap: 0.65rem;">
        <!-- Live Count Quick Link -->
        <li class="nav-item d-none d-sm-block">
            <a href="/live-count" target="_blank" class="d-inline-flex align-items-center font-display font-semibold text-xs px-3.5 py-2 rounded-full text-emerald-700 hover:text-emerald-800 transition-all shadow-sm"
               style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); gap: 0.4rem;">
                <i class="fas fa-broadcast-tower text-emerald-600"></i>
                <span>Live Count</span>
            </a>
        </li>

        <!-- Fullscreen Button -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center text-slate-600 hover:text-ocean-600 transition-all" 
               data-widget="fullscreen" 
               href="#" 
               role="button"
               title="Layar Penuh"
               style="width: 38px; height: 38px; border-radius: 12px; background: rgba(241, 245, 249, 0.9); border: 1px solid rgba(226, 232, 240, 0.8);">
                <i class="fas fa-expand-arrows-alt" style="font-size: 0.9rem;"></i>
            </a>
        </li>

        <!-- Admin Profile Mini Pill -->
        <li class="nav-item ml-1">
            <div class="d-flex align-items-center px-2.5 py-1.5 rounded-full" style="background: rgba(248, 250, 252, 0.95); border: 1px solid rgba(226, 232, 240, 0.9); gap: 0.5rem;">
                <div class="rounded-full bg-ocean-500 text-white font-display font-bold text-xs d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="d-none d-lg-block text-left pr-1.5" style="line-height: 1.15;">
                    <div class="font-display font-bold text-xs text-ocean-950 truncate" style="max-width: 120px;">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </div>
                    <span class="text-[10px] font-medium text-slate-500">Administrator</span>
                </div>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->