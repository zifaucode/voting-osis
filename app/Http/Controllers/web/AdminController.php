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
        $osisCandidate = OsisChairmanCandidate::all();
        $candidateName = collect($osisCandidate)->pluck('name');
        // return $candidateName;
        $userTotal = User::where('role', 2)->whereNot('id', 1)->count();

        $userAddictionHighTotal = 0;
        $userAddictionMediumTotal = 0;
        $userAddictionLowTotal = 0;
        $userNotAddictionTotal = 0;

        $userNotAddictionList = [];
        $userAddictionLowList = [];
        $userAddictionMediumList = [];
        $userAddictionHighList = [];





        return view('admin.dashboard.index', [
            'user_total' => $userTotal,
            'user_addiction_high_total' => $userAddictionHighTotal,
            'user_addiction_medium_total' => $userAddictionMediumTotal,
            'user_addiction_low_total' => $userAddictionLowTotal,
            'user_not_addiction_total' => $userNotAddictionTotal,

            'user_not_addiction_list' => $userNotAddictionList,
            'user_low_addiction_list' => $userAddictionLowList,
            'user_medium_addiction_list' => $userAddictionMediumList,
            'user_high_addiction_list' => $userAddictionHighList,

            // 'chart' => $chart[0],
            'chart' => [],
            'osis_candidate' => $osisCandidate,
            'candidate_name' => $candidateName,
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
