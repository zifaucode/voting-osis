<footer class="main-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 1.25rem 1.75rem; color: #475569; font-size: 0.875rem;">
    <div class="container-fluid px-0">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-left" style="gap: 0.85rem;">
            <!-- Left: Brand, Copyright & Status -->
            <div class="d-flex align-items-center flex-wrap justify-content-center justify-content-md-start" style="gap: 0.65rem;">
                <span class="font-weight-bold" style="color: #0f172a; font-size: 0.95rem; letter-spacing: -0.3px;">
                    <i class="fas fa-vote-yea text-success mr-1"></i>
                    {{ $web_setting?->web_title ?? 'E-Pemilos' }}
                </span>
                <span class="text-muted small">&copy; {{ date('Y') }} All rights reserved.</span>
                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #065f46; border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 999px; padding: 0.3rem 0.75rem; font-weight: 600; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <span style="display: inline-block; width: 7px; height: 7px; background-color: #10b981; border-radius: 50%; box-shadow: 0 0 8px #10b981;"></span>
                    Sistem Pemilos Aktif
                </span>
            </div>

            <!-- Right: Developer Credits with sleek Instagram Pill -->
            <div class="d-flex align-items-center justify-content-center" style="gap: 0.5rem;">
                <a href="https://instagram.com/zidan_kebanyakan_tugas" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.32rem 0.85rem; border-radius: 999px; background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; text-decoration: none; font-weight: 700; font-size: 0.825rem; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 1px 2px rgba(225, 29, 72, 0.05);"
                   onmouseover="this.style.background='#ffe4e6'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(225, 29, 72, 0.15)';"
                   onmouseout="this.style.background='#fff1f2'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(225, 29, 72, 0.05)';">
                    <i class="fab fa-instagram" style="font-size: 1rem; color: #e1306c;"></i>
                    <span>Zidan Fathul</span>
                    <span style="font-weight: 500; opacity: 0.8; font-size: 0.75rem;">(@zidan_kebanyakan_tugas)</span>
                </a>
            </div>
        </div>
    </div>
</footer>