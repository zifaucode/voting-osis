<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Anti-Brute Force Login Pemilih (Lab-Friendly: dibatasi per kombinasi NISN + IP)
        // 120 bilik di 3 lab komputer tidak akan saling memblokir karena key terisolasi per NISN.
        // Limit dilonggarkan ke 15 percobaan/menit agar siswa yang salah ketik kode akses tidak langsung terkunci.
        RateLimiter::for('voter-login', function (Request $request) {
            $key = trim(strtolower((string)$request->input('nisn', 'guest'))) . '|' . $request->ip();
            return Limit::perMinute(15)->by($key)->response(function () {
                return back()
                    ->withInput(request()->only('nisn'))
                    ->with('error', 'Terlalu banyak percobaan login gagal untuk akun ini. Harap tunggu 1 menit sebelum mencoba kembali.');
            });
        });

        // Anti-Brute Force Login Administrator
        // Dibatasi maksimal 5 percobaan per menit per kombinasi username + IP
        RateLimiter::for('admin-login', function (Request $request) {
            $key = trim(strtolower((string)$request->input('username', 'admin'))) . '|' . $request->ip();
            return Limit::perMinute(5)->by($key)->response(function () {
                return back()
                    ->withInput(request()->only('username'))
                    ->with('error', 'Terlalu banyak percobaan login admin yang gagal. Harap tunggu 1 menit sebelum mencoba kembali.');
            });
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
