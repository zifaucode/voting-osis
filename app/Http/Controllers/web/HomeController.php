<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\OsisChairmanCandidate;
use App\Models\WebSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $osisCandidate = OsisChairmanCandidate::all();
        $webSetting = WebSetting::first();
        $yearPlus = '';
        if (isset($webSetting)) {
            $yearPlus = Carbon::parse($webSetting->year_period)->addYears(1)->format('Y');
        }
        $webSetting = WebSetting::first();
        // return $osisCandidate;
        return view('frontend.dashboard.index', [
            'osis_candidate' => $osisCandidate,
            'web_setting' => $webSetting,
            'year_plus' => $yearPlus,
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
