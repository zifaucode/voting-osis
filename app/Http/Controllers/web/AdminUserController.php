<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\AnalysisResult;
use App\Models\ResultQuestion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('role', 2)->whereNot('id', 1)->get();

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
        }

        return view('admin.user.index', [
            'user' => $user,

            'user_addiction_high_total' => $userAddictionHighTotal,
            'user_addiction_medium_total' => $userAddictionMediumTotal,
            'user_addiction_low_total' => $userAddictionLowTotal,
            'user_not_addiction_total' => $userNotAddictionTotal,

            'user_not_addiction_list' => $userNotAddictionList,
            'user_low_addiction_list' => $userAddictionLowList,
            'user_medium_addiction_list' => $userAddictionMediumList,
            'user_high_addiction_list' => $userAddictionHighList,
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

    public function resetQuestion(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $resetQuestion =  User::with(['analysisResult', 'resultQuestion'])->findOrFail($id);
            $resetQuestion->answer_status = null;
            $resetQuestion->answer_result = null;
            $resetQuestion->save();

            // $resetAnalysisResult = AnalysisResult::where('user_id', $id)->first();
            if ($resetQuestion->analysisResult != null) {
                $resetQuestion->analysisResult()->delete();
            }

            // $resetResultQuestion = ResultQuestion::where('user_id', $id)->first();
            if ($resetQuestion->resultQuestion != null) {
                $resetQuestion->resultQuestion()->delete();
            }


            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $resetQuestion,
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

    public function import_excel(Request $request)
    {
        // validation
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // get file excel
        $file = $request->file('file');

        // create unique file name
        $nama_file = rand() . $file->getClientOriginalName();

        // upload file to public folder
        $file->move('file/excel', $nama_file);

        // import data
        Excel::import(new UsersImport, 'file/excel/' . $nama_file);


        // redirect to main page upload
        return redirect('/admin/user');
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
        try {
            DB::beginTransaction();
            $deleteUser =  User::with(['analysisResult', 'resultQuestion'])->findOrFail($id);

            // $resetAnalysisResult = AnalysisResult::where('user_id', $id)->first();
            if ($deleteUser->analysisResult != null) {
                $deleteUser->analysisResult()->delete();
            }

            // $resetResultQuestion = ResultQuestion::where('user_id', $id)->first();
            if ($deleteUser->resultQuestion != null) {
                $deleteUser->resultQuestion()->delete();
            }
            $deleteUser->delete();


            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $deleteUser,
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
}
