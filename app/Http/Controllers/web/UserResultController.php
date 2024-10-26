<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\AnalysisResult;
use App\Models\ResultQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $analysisResult = AnalysisResult::with('addictionLevel')->where('user_id', $user->id)->first();
        $symptomName = [];
        if (isset($analysisResult->symptom_name)) {
            $symptomName = json_decode($analysisResult->symptom_name);
        };

        $resultQuestion = ResultQuestion::where('user_id', $user->id)->with(['user', 'options', 'question'])->get();
        $countResultQuestion = collect($resultQuestion)->count();

        // return $analysisResult;
        return view('user.result.index', [
            'result_question' => $resultQuestion,
            'symptom_name' => $symptomName,
            'count_result_question' => $countResultQuestion,
            'analysis_result' => $analysisResult,
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
