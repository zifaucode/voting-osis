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
        $users = User::select('id', 'name', 'nisn', 'class', 'code_access', 'vote_status')
            ->where('role', 2)
            ->where('id', '!=', 1)
            ->orderBy('id', 'asc')
            ->get();

        $users->transform(function ($u) {
            $classUpper = strtoupper(trim((string)$u->class));
            $nisnUpper = strtoupper(trim((string)$u->nisn));
            $u->is_teacher = (
                str_contains($classUpper, 'GURU') ||
                str_contains($classUpper, 'GUKAR') ||
                str_contains($classUpper, 'KARYAWAN') ||
                str_contains($classUpper, 'TATA USAHA') ||
                str_starts_with($nisnUpper, 'GUKAR') ||
                str_starts_with($nisnUpper, 'GURU') ||
                str_starts_with($nisnUpper, 'G-')
            );
            return $u;
        });

        return view('admin.user.index', [
            'user' => $users,
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
            'file' => 'required|mimes:csv,xls,xlsx|max:10240'
        ]);

        // get file excel
        $file = $request->file('file');

        // create unique secure file name in private storage
        $fileName = 'import_' . time() . '_' . \Illuminate\Support\Str::random(12) . '.' . $file->getClientOriginalExtension();
        $importDir = storage_path('app/imports');
        if (!file_exists($importDir)) {
            mkdir($importDir, 0750, true);
        }

        $fullPath = $file->move($importDir, $fileName);

        try {
            // import data
            Excel::import(new UsersImport, $fullPath);
        } finally {
            // Hapus file excel secara instan agar data kredensial tidak tertinggal di server
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        // Refresh cache live vote payload
        \Illuminate\Support\Facades\Cache::forget('live_votes_api_payload');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Data Pemilih berhasil diimpor.',
                'code' => 200,
                'error' => false,
            ]);
        }

        // redirect to main page upload
        return redirect('/admin/user')->with('success', 'Data Pemilih berhasil diimpor.');
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
            $deleteUser = User::with('totalVoteUser')->findOrFail($id);

            // Hapus rekaman suara terkait jika pemilih sudah memilih
            \App\Models\TotalVoteUser::where('user_id', $deleteUser->id)->delete();
            $deletedId = $deleteUser->id;
            $deletedName = $deleteUser->name;
            $deleteUser->delete();

            DB::commit();

            // Refresh cache dan pancarkan update realtime
            \Illuminate\Support\Facades\Cache::forget('live_votes_api_payload');
            \App\Services\RealtimeBroadcaster::broadcast();

            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => [
                    'id' => $deletedId,
                    'name' => $deletedName,
                ],
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
            $userIds = $request->userIds;
            if (!empty($userIds) && is_array($userIds)) {
                // Hanya hapus akun pemilih (role = 2), JANGAN hapus akun Admin
                $targetIds = User::whereIn('id', $userIds)->where('role', 2)->pluck('id');
                \App\Models\TotalVoteUser::whereIn('user_id', $targetIds)->delete();
                User::whereIn('id', $targetIds)->delete();
            }

            DB::commit();

            // Refresh cache dan pancarkan update realtime
            \Illuminate\Support\Facades\Cache::forget('live_votes_api_payload');
            \App\Services\RealtimeBroadcaster::broadcast();

            return response()->json([
                'message' => 'Data pemilih yang dipilih berhasil dihapus.',
                'code' => 200,
                'error' => false,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
                'code' => 500,
                'error' => true,
            ], 500);
        }
    }

    /**
     * Bulk Delete: Kosongkan seluruh data pemilih (role = 2).
     */
    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();
            $voterIds = User::where('role', 2)->pluck('id');
            \App\Models\TotalVoteUser::whereIn('user_id', $voterIds)->delete();
            User::where('role', 2)->delete();

            DB::commit();

            \Illuminate\Support\Facades\Cache::forget('live_votes_api_payload');
            \App\Services\RealtimeBroadcaster::broadcast();

            return response()->json([
                'message' => 'Semua data pemilih berhasil dikosongkan.',
                'code' => 200,
                'error' => false,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengosongkan data pemilih: ' . $e->getMessage(),
                'code' => 500,
                'error' => true,
            ], 500);
        }
    }
}
