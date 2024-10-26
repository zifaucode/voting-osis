<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\AnalysisResult;
use App\Models\Question;
use App\Models\ResultQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $question = Question::with('options')->get();
        $userResult = User::find($user->id);
        $analysisResult = AnalysisResult::with('addictionLevel')->where('user_id', $user->id)->first();

        $symptomName = [];
        if (isset($analysisResult->symptom_name)) {
            $symptomName = json_decode($analysisResult->symptom_name);
        };


        // return $symptomName;
        return view('user.question.index', [
            'question' => $question,
            'user_result' => $userResult,
            'analysis_result' => $analysisResult,
            'symptom_name' => $symptomName,
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
