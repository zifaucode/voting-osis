<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userTotal = User::where('role', 2)->whereNot('id', 1)->count();
        $user = User::with(['analysisResult', 'resultQuestion'])->whereNot('id', 1)->get();
        $useFilter = collect($user)->filter(function ($q) {
            return $q->analysisResult != null;
        });
        $userAddictionHighTotal = 0;
        $userAddictionMediumTotal = 0;
        $userAddictionLowTotal = 0;
        $userNotAddictionTotal = 0;

        $userNotAddictionList = [];
        $userAddictionLowList = [];
        $userAddictionMediumList = [];
        $userAddictionHighList = [];


        if (isset($useFilter)) {
            $userNotAddictionTotal = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 1;
            })->count();
            $userNotAddictionList = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 1;
            })->all();


            $userAddictionLowTotal = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 2;
            })->count();
            $userAddictionLowList = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 2;
            })->all();


            $userAddictionMediumTotal = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 3;
            })->count();
            $userAddictionMediumList = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 3;
            })->all();


            $userAddictionHighTotal = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 4;
            })->count();
            $userAddictionHighList = collect($useFilter)->filter(function ($filter) {
                return $filter->analysisResult->addiction_level_id == 4;
            })->all();

            // $userNotAddictionTotal = collect($$userNotAddictionTotal)->count();

            $chart  = collect($useFilter)->map(function ($filter) use ($userNotAddictionTotal, $userAddictionLowTotal, $userAddictionMediumTotal, $userAddictionHighTotal) {
                return [
                    $userNotAddictionTotal,
                    $userAddictionLowTotal,
                    $userAddictionMediumTotal,
                    $userAddictionHighTotal,
                ];
            })->values();
        }






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


            'chart' => $chart[0],
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
