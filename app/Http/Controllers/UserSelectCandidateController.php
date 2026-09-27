<?php

namespace App\Http\Controllers;

use App\Models\TotalVoteUser;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSelectCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'candidate_id' => 'required|integer|exists:osis_chairman_candidates,id',
        ]);

        // Pastikan pemilih sudah login
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Sesi login Anda telah berakhir. Silakan login kembali.',
                'code' => 401,
            ], 401);
        }

        $currentUser = auth()->user();

        // Pastikan akun adalah pemilih yang sah (role == 2)
        if ($currentUser->role != 2) {
            return response()->json([
                'message' => 'Akun Administrator tidak memiliki hak suara dalam bilik pemilihan.',
                'code' => 403,
            ], 403);
        }

        // 1. Validasi Jadwal Waktu Pemilihan (WIB / Asia/Jakarta) via Cache
        $webSetting = \Illuminate\Support\Facades\Cache::rememberForever('pemilos_web_setting', function () {
            return \App\Models\WebSetting::first();
        });

        if ($webSetting && !empty($webSetting->start_date) && !empty($webSetting->start_time) && !empty($webSetting->end_time)) {
            $tz = 'Asia/Jakarta';
            $start = Carbon::parse($webSetting->start_date . ' ' . $webSetting->start_time, $tz);
            $end = Carbon::parse($webSetting->start_date . ' ' . $webSetting->end_time, $tz);
            $now = Carbon::now($tz);

            if ($now->lt($start)) {
                return response()->json([
                    'message' => 'Maaf, sesi pemungutan suara belum dimulai. Jadwal dibuka pukul ' . substr($webSetting->start_time, 0, 5) . ' WIB.',
                    'code' => 400,
                ], 400);
            }

            if ($now->gt($end)) {
                return response()->json([
                    'message' => 'Maaf, sesi pemungutan suara telah ditutup pada pukul ' . substr($webSetting->end_time, 0, 5) . ' WIB.',
                    'code' => 400,
                ], 400);
            }
        }

        // 2. Transaksi Database dengan Row-Level Locking (Pessimistic Locking)
        try {
            $result = DB::transaction(function () use ($request, $currentUser) {
                // Pada SQLite WAL, paksa akuisisi write lock di awal agar terhindar dari upgrade deadlock saat konkurensi tinggi
                if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
                    DB::statement('UPDATE total_vote_users SET id = id WHERE 1=0');
                }

                // Lock baris user berdasarkan ID sesi yang aktif
                $getUser = User::where('id', $currentUser->id)->lockForUpdate()->first();

                if (!$getUser) {
                    return [
                        'error' => true,
                        'code' => 400,
                        'message' => 'Data pemilih tidak ditemukan.',
                    ];
                }

                if ($getUser->vote_status != 0 || $getUser->is_vote !== null) {
                    return [
                        'error' => true,
                        'code' => 400,
                        'message' => 'Anda sudah pernah menggunakan hak suara. Satu pemilih hanya dapat memilih 1 kali.',
                    ];
                }

                // Simpan suara ke total_vote_users
                $userSelectCandidate = new TotalVoteUser();
                $userSelectCandidate->user_id = $getUser->id;
                $userSelectCandidate->candidate_id = $request->candidate_id;
                $userSelectCandidate->time = Carbon::now()->format('H:i:s');
                $userSelectCandidate->save();

                // Update status user menjadi sudah memilih
                $getUser->vote_status = 1;
                $getUser->is_vote = $request->candidate_id;
                $getUser->save();

                return [
                    'error' => false,
                    'code' => 200,
                    'message' => 'Suara Anda telah berhasil dicatat oleh sistem.',
                    'data' => $userSelectCandidate,
                ];
            }, 5);

            if ($result['error']) {
                return response()->json([
                    'message' => $result['message'],
                    'code' => $result['code'],
                ], $result['code']);
            }

            // Invalidate cache live votes agar live-count langsung membaca data terbaru
            \Illuminate\Support\Facades\Cache::forget('live_votes_api_payload');

            // Pancarkan pembaruan secara async via queue agar tidak memblokir proses voting
            \App\Jobs\BroadcastVoteUpdate::dispatch();

            // Otomatis logout pemilih dari sistem setelah berhasil memilih
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => $result['message'],
                'code' => 200,
                'error' => false,
                'data' => $result['data'],
            ]);
        } catch (\Illuminate\Database\QueryException $qe) {
            // Tangani error duplicate constraint jika terjadi tabrakan milidetik
            if ($qe->getCode() == 23000) {
                return response()->json([
                    'message' => 'Maaf, hak suara untuk kode akses ini sudah tercatat.',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan sistem saat mencatat suara.',
                'code' => 500,
                'error' => true,
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan internal pada server.',
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
