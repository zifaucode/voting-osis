<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\AddictionLevel;
use App\Models\Option;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddictionLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addictionLevel = AddictionLevel::all();
        // return $addictionLevel;
        return view('admin.addiction-level.index', [
            'addiction_level' => $addictionLevel,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.addiction-level.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $addictionList = $request->addictionLevelList;

            foreach ($addictionList as $addiction) {
                $numb = AddictionLevel::max('id');
                $number = "P-" . sprintf("%03d", $numb + 1);
                $newAddictionLevel = new AddictionLevel();
                $newAddictionLevel->code =  $number;
                $newAddictionLevel->level = $addiction['addiction_level'];
                $newAddictionLevel->solution = $addiction['solution'];
                $newAddictionLevel->start_score = $addiction['start_score'];
                $newAddictionLevel->end_score = $addiction['end_score'];
                $newAddictionLevel->save();
            }

            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $newAddictionLevel,
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
        $addictionLevel = AddictionLevel::findOrFail($id);
        // return $addictionLevel;
        return view('admin.addiction-level.edit', [
            'get_addiction_level' => $addictionLevel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $updateAddictionLevel = AddictionLevel::findOrFail($id);
            $updateAddictionLevel->level = $request->addiction_level;
            $updateAddictionLevel->solution = $request->solution;
            $updateAddictionLevel->start_score = $request->start_score;
            $updateAddictionLevel->end_score = $request->end_score;
            $updateAddictionLevel->save();

            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $updateAddictionLevel,
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $addiction = AddictionLevel::find($id);
            $addiction->delete();
            return [
                'message' => 'data has been deleted',
                'error' => false,
                'code' => 200,
            ];
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e->getMessage(),
                'line' => $e->getLine(),
            ];
        }
    }
}
