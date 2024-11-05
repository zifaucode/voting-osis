<?php

namespace App\Http\Controllers;

use App\Models\TotalVoteUser;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSelectCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try {
            DB::beginTransaction();
            $code = $request->code_access;
            $getUser = User::where('code_access', $code)->first();

            if (isset($getUser)) {
                if ($getUser->vote_status == 0) {
                    $userSelectCandidate = new TotalVoteUser();
                    $userSelectCandidate->user_id = $getUser->id;
                    $userSelectCandidate->candidate_id = $request->candidate_id;
                    $userSelectCandidate->time =  Carbon::now()->format('H:i:s');
                    $userSelectCandidate->save();


                    $updateStatusVote = User::find($getUser->id);
                    $updateStatusVote->vote_status = 1;
                    $updateStatusVote->is_vote = $request->candidate_id;
                    $updateStatusVote->save();
                } else {
                    return response()->json([
                        'message' => 'Maaf, Kode Akses sudah digunakan untuk memilih',
                        'code' => 400,
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Maaf, Kode akses yang dimasukan salah',
                    'code' => 400,
                ], 400);
            }



            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $userSelectCandidate,
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
