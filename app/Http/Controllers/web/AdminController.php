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
        $user = User::with('selectCandidate')->where('role', 2)->get();
        $userCount = collect($user)->count();

        $notYetChosen = collect($user->where('vote_status', 0))->count();
        $alreadyChosen = collect($user->where('vote_status', 1))->count();

        $osisCandidate = OsisChairmanCandidate::with('totalVotes')->get()
            ->each(function ($q) use ($userCount) {
                $q['total'] = collect($q->totalVotes)->count();
                $q['percentase'] = $userCount > 0 ? ($q['total'] / $userCount) * 100 : 0;
            });
        // return $notYetChosen;


        $candidateName = collect($osisCandidate)->pluck('name');
        $candidateTotal = collect($osisCandidate)->pluck('total');



        return view('admin.dashboard.index', [
            'user_total' => $userCount,
            'candidate_total' => $candidateTotal,
            // 'chart' => $chart[0],
            'chart' => [],
            'osis_candidate' => $osisCandidate,
            'candidate_name' => $candidateName,
            'not_yet_chosen' => $notYetChosen,
            'already_chosen' => $alreadyChosen,
        ]);
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
