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
        // 40 siswa dengan NISN berbeda di satu lab komputer tidak akan saling memblokir
        RateLimiter::for('voter-login', function (Request $request) {
            $key = trim(strtolower((string)$request->input('nisn', 'guest'))) . '|' . $request->ip();
            return Limit::perMinute(6)->by($key)->response(function () {
                return back()
                    ->withInput(request()->only('nisn'))
                    ->with('error', 'Terlalu banyak percobaan login gagal untuk akun ini. Harap tunggu 1 menit sebelum mencoba kembali.');
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
