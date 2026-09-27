<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\OsisChairmanCandidate;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userCount = User::where('role', 2)->count();
        $alreadyChosen = User::where('role', 2)->where('vote_status', 1)->count();
        $notYetChosen = max(0, $userCount - $alreadyChosen);

        $osisCandidate = OsisChairmanCandidate::withCount('totalVotes')
            ->orderBy('sequence_number', 'asc')
            ->get()
            ->each(function ($q) use ($userCount) {
                $q['total'] = $q->total_votes_count ?? 0;
                $q['percentase'] = $userCount > 0 ? round(($q['total'] / $userCount) * 100, 1) : 0;
            });
        // return $notYetChosen;


        $candidateName = collect($osisCandidate)->pluck('name');
        $candidateTotal = collect($osisCandidate)->pluck('total');



        $webSetting = \App\Models\WebSetting::first();

        return view('admin.dashboard.index', [
            'user_total' => $userCount,
            'candidate_total' => $candidateTotal,
            'chart' => [],
            'osis_candidate' => $osisCandidate,
            'candidate_name' => $candidateName,
            'not_yet_chosen' => $notYetChosen,
            'already_chosen' => $alreadyChosen,
            'web_setting' => $webSetting,
        ]);
    }

    /**
     * Reset seluruh suara masuk (kembalikan ke 0 dan status voter kembali belum memilih)
     */
    public function resetAllVotes()
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Kosongkan semua data suara
            \App\Models\TotalVoteUser::truncate();

            // 2. Kembalikan status pemilih siswa ke belum memilih
            \App\Models\User::where('role', 2)->update([
                'vote_status' => 0,
                'is_vote' => null,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            // 3. Hapus cache agregat suara dan paslon
            \Illuminate\Support\Facades\Cache::forget('live_votes_api_payload');
            \Illuminate\Support\Facades\Cache::forget('pemilos_candidates_list');

            // 4. Siarkan perubahan ke seluruh layar proyektor / live count dan dashboard
            \App\Services\RealtimeBroadcaster::broadcast();

            return response()->json([
                'error' => false,
                'code' => 200,
                'message' => 'Seluruh suara masuk berhasil di-reset menjadi 0. Status seluruh siswa kembali belum memilih.',
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'error' => true,
                'code' => 500,
                'message' => 'Gagal mereset suara: ' . $e->getMessage(),
            ], 500);
        }
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
