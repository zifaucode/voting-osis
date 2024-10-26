<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\AddictionLevel;
use App\Models\Question;
use App\Models\Rules;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rules = Rules::all();
        $rules = collect($rules)->map(function ($q) {
            $arrayCode = json_decode($q->symptom_code);
            $question = Question::whereIn('id', $arrayCode)->get();
            return [
                'id' => $q->id,
                'addiction_level_id' => $q->addiction_level_id,
                'level' => $q->level,
                'solution' => $q->solution,
                'symptom_code' =>  $question,
                'symptom_name' => $q->symptom_name,
                'score' => $q->score,
            ];
        });
        // return $rules;
        return view('admin.rules.index', [
            'rules' => $rules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $question = Question::all();
        $getAddictionLevel = AddictionLevel::all();
        // return $question;
        return view('admin.rules.create', [
            'question' => $question,
            'get_addiction_level' => $getAddictionLevel,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            // $getAddictionLevel = AddictionLevel::find($request->addiction_level_id);
            $newRules = new Rules();
            // $newRules->addiction_level_id = $request->addiction_level_id;
            // $newRules->level = $getAddictionLevel->level;
            // $newRules->solution = $getAddictionLevel->solution;
            $newRules->symptom_code = $request->selectedQuestion;
            $newRules->symptom_name = $request->symptom_name;
            $newRules->score = $request->score;
            $newRules->save();

            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $newRules,
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
        $question = Question::all();
        $getAddictionLevel = AddictionLevel::all();
        $rules = Rules::findOrFail($id);
        // return $rules;
        return view('admin.rules.edit', [
            'question' => $question,
            'get_addiction_level' => $getAddictionLevel,
            'rules' => $rules,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $newRules = Rules::find($id);
            $newRules->symptom_code = $request->selectedQuestion;
            $newRules->symptom_name = $request->symptom_name;
            $newRules->score = $request->score;
            $newRules->save();

            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $newRules,
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
            $rules = Rules::find($id);
            $rules->delete();
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
