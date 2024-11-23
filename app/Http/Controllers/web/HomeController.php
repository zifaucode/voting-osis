<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\OsisChairmanCandidate;
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


    public function showOff()
    {
        $osisCandidate = OsisChairmanCandidate::all();
        $webSetting = WebSetting::first();
        $yearPlus = '';
        if (isset($webSetting)) {
            $yearPlus = Carbon::parse($webSetting->year_period)->addYears(1)->format('Y');
        }
        $webSetting = WebSetting::first();
        // return $osisCandidate;
        return view('frontend.dashboard.show-off', [
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

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $updateStatus = WebSetting::first();
            $updateStatus->status = 'dibuka';
            $updateStatus->save();


            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $updateStatus,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal error',
                'code' => 500,
                'error' => true,
                'errors' => $e->getMessage(),
                'line' => $e->getLine(),
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
