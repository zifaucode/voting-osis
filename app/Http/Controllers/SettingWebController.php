<?php

namespace App\Http\Controllers;

use App\Models\WebSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $webSetting = WebSetting::first();
        // return  $webSetting;
        return view('admin.web-setting.index', [
            'web_setting' => $webSetting,
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
    public function storeFrontPage(Request $request)
    {
        try {
            DB::beginTransaction();
            $id =  $request->id;
            if ($id == 0) {
                $newWebSetting = new WebSetting();
                $newWebSetting->web_title = $request->webTitle;
                $newWebSetting->school_name = $request->schoolName;
                $newWebSetting->year_period = $request->yearPeriod;
                $newWebSetting->save();
            } else {
                $updateWebSetting = WebSetting::findOrFail($id);
                $updateWebSetting->web_title = $request->webTitle;
                $updateWebSetting->school_name = $request->schoolName;
                $updateWebSetting->year_period = $request->yearPeriod;
                $updateWebSetting->save();
            }



            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
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

    public function storeVotingTime(Request $request)
    {
        try {
            DB::beginTransaction();
            $id =  $request->id;
            if ($id == 0) {
                $newWebSetting = new WebSetting();
                $newWebSetting->start_date = $request->startDate;
                $newWebSetting->start_time = $request->startTime;
                $newWebSetting->end_time = $request->endTime;
                $newWebSetting->status = 'dibuka';
                $newWebSetting->save();
            } else {
                $updateWebSetting = WebSetting::findOrFail($id);
                $updateWebSetting->start_date = $request->startDate;
                $updateWebSetting->start_time = $request->startTime;
                $updateWebSetting->end_time = $request->endTime;
                $updateWebSetting->status = 'dibuka';
                $updateWebSetting->save();
            }

            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
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
