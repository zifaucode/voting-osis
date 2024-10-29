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

        return view('admin.user.index', [
            'user' => $user,
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
            $deleteUser =  User::findOrFail($id);
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

    public function selectedDestroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $userId = $request->userIds;
            foreach ($userId as $user) {
                $deleteUser =  User::findOrFail($user);
                $deleteUser->delete();
            }

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
