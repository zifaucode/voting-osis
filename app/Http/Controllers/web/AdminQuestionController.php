<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $question = Question::with('options')->get();
        // return $question;
        return view('admin.question.index', [
            'question' => $question,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.question.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $questionList = $request->questionList;
            foreach ($questionList as $question) {
                $numb = Question::max('id');
                $number = "G-" . sprintf("%03d", $numb + 1);
                $newQuestion = new Question();
                $newQuestion->question_content = $question['question'];
                $newQuestion->number = $number;
                $newQuestion->save();
                foreach ($question['option'] as $option) {
                    $newOption = new Option();
                    $newOption->question_id = $newQuestion->id;
                    $newOption->option = $option['opt'];
                    $newOption->cf_value = $option['cf'];
                    $newOption->mb_value = $option['mb'];
                    $newOption->md_value = $option['md'];
                    $newOption->save();
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $newQuestion,
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
        $question = Question::with('options')->findOrFail($id);
        // return $question;
        return view('admin.question.edit', [
            'question' => $question,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $updateQuestion =  Question::findOrFail($id);
            $updateQuestion->question_content = $request->questionList['question_content'];
            $updateQuestion->save();
            foreach ($request->questionList['options'] as $option) {
                $newOption =  Option::findOrFail($option['id']);
                $newOption->option = $option['option'];
                $newOption->cf_value = $option['cf_value'];
                $newOption->mb_value = $option['mb_value'];
                $newOption->md_value = $option['md_value'];
                $newOption->save();
            }



            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $updateQuestion,
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
            $question = Question::with('option')->find($id);
            if (isset($question)) {
                if (isset($question->option)) {
                    $question->option()->delete();
                }
                $question->delete();
            }

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
