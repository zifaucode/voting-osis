<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\OsisChairmanCandidate;
use App\Models\User;
use App\Models\WebSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $webSetting = \Illuminate\Support\Facades\Cache::rememberForever('pemilos_web_setting', function () {
            return WebSetting::first();
        });

        if (!$webSetting || empty($webSetting->start_date) || empty($webSetting->end_time)) {
            return view('frontend.dashboard.default', [
                'web_setting' => $webSetting
            ]);
        }

        // Pemilih harus login terlebih dahulu dengan NIS dan Kode Akses sebelum masuk ke bilik suara
        if (!auth()->check()) {
            return redirect()->route('login.user');
        }

        // Jika yang login adalah Admin (role 1), arahkan ke dashboard admin
        if (auth()->user()->role == 1) {
            return redirect()->route('dashboard');
        }

        // Jika pemilih sudah pernah memilih, otomatis logout dan larang akses kembali
        if (auth()->user()->vote_status != 0) {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login.user')->with('error', 'Hak suara Anda telah digunakan. Anda tidak dapat login kembali.');
        }

        // Validasi sesi aktif: pastikan perangkat ini adalah bilik suara yang sah (cegah multi-login)
        $activeSessionId = \Illuminate\Support\Facades\Cache::get('active_voter_' . auth()->id());
        if ($activeSessionId && $activeSessionId !== request()->session()->getId()) {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login.user')->with('error', 'Sesi bilik suara Anda telah dialihkan atau dibuka di perangkat lain.');
        }

        // Perbarui masa aktif sesi bilik (5 menit dari aktivitas terakhir)
        \Illuminate\Support\Facades\Cache::put('active_voter_time_' . auth()->id(), now(), now()->addMinutes(5));

        // Cache daftar paslon untuk efisiensi konkurensi bilik suara
        $osisCandidate = \Illuminate\Support\Facades\Cache::remember('pemilos_candidates_list', 3600, function () {
            return OsisChairmanCandidate::orderBy('sequence_number', 'asc')->get();
        });

        $yearPlus = (isset($webSetting) && $webSetting->year_period) 
            ? (string)((int)$webSetting->year_period + 1) 
            : '';

        return view('frontend.dashboard.index', [
            'osis_candidate' => $osisCandidate,
            'web_setting' => $webSetting,
            'year_plus' => $yearPlus,
            'current_user' => auth()->user(),
        ]);
    }


    public function showOff()
    {
        $webSetting = WebSetting::first();

        if (!$webSetting || empty($webSetting->start_date) || empty($webSetting->end_time)) {
            return view('frontend.dashboard.default', [
                'web_setting' => $webSetting
            ]);
        }

        $osisCandidate = OsisChairmanCandidate::all();
        $yearPlus = (isset($webSetting) && $webSetting->year_period) 
            ? (string)((int)$webSetting->year_period + 1) 
            : '';
        return view('frontend.dashboard.show-off', [
            'osis_candidate' => $osisCandidate,
            'web_setting' => $webSetting,
            'year_plus' => $yearPlus,
        ]);
    }

    public function liveCount()
    {
        // Live Count hanya diizinkan untuk Admin (role = 1)
        if (!auth()->check() || auth()->user()->role != 1) {
            return redirect()->route('login')->with('error', 'Halaman Live Count hanya dapat diakses oleh Administrator.');
        }

        $webSetting = WebSetting::first();
        $yearPlus = ($webSetting && $webSetting->year_period) 
            ? (string)((int)$webSetting->year_period + 1) 
            : '';

        $initialPayload = \App\Services\RealtimeBroadcaster::getCurrentVotePayload();

        // Segarkan in-memory cache pada server Socket.io Node.js
        \App\Services\RealtimeBroadcaster::broadcast($initialPayload);

        return view('frontend.dashboard.live-count', [
            'web_setting' => $webSetting,
            'year_plus' => $yearPlus,
            'initial_payload' => $initialPayload,
        ]);
    }

    public function liveVotesApi()
    {
        // API Live count hanya untuk Admin (role = 1)
        if (!auth()->check() || auth()->user()->role != 1) {
            return response()->json(['error' => 'Akses ditolak. Hanya administrator yang dapat mengakses live count.'], 403);
        }

        $payload = \Illuminate\Support\Facades\Cache::remember('live_votes_api_payload', 2, function () {
            return \App\Services\RealtimeBroadcaster::getCurrentVotePayload();
        });

        return response()->json($payload);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $updateStatus = WebSetting::first();
            if ($updateStatus) {
                $updateStatus->status = 'dibuka';
                $updateStatus->save();
            }

            DB::commit();
            \Illuminate\Support\Facades\Cache::forget('pemilos_web_setting');

            return response()->json([
                'message' => 'Status pemilihan berhasil dibuka.',
                'code' => 200,
                'error' => false,
                'data' => $updateStatus,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengubah status pemilihan.',
                'code' => 500,
                'error' => true,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
