<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\AddictionLevel;
use App\Models\AnalysisResult;
use App\Models\Option;
use App\Models\Question;
use App\Models\ResultQuestion;
use App\Models\Rules;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultQuestionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $questionList = $request->questionList;
            $cfScore = 0;
            $getIdValue = [];
            $getSymptomName = [];
            foreach ($questionList as $question) {
                $user = Auth::user();
                $newQuestion = new ResultQuestion();
                $newQuestion->user_id = $user->id;
                $newQuestion->question_id = $question['id'];
                $newQuestion->option_selected_id = $question['value'];

                $option = Option::find($question['value']);
                if ($option->cf_value > 0) {
                    $newQuestion->result = 1;
                } else {
                    $newQuestion->result = 0;
                }
                $newQuestion->save();

                $getRules = Rules::all();
                foreach ($getRules as $rule) {
                    $symptomCodes = json_decode($rule['symptom_code'], true);
                    if ($newQuestion->result == 1) {
                        if (in_array('1', $symptomCodes)) {
                            $cfScore++;
                            // break;
                        }
                    }
                }

                if ($newQuestion->result === 1) {
                    $getIdValue[] = $question['id'];
                }
            }
            $getRules = Rules::all();
            $addictionScore = 0;
            foreach ($getRules as $rule) {
                $symptomCodes = json_decode($rule['symptom_code'], true);
                $symptomCodes = array_map('intval', $symptomCodes);
                $perbedaan = array_diff($symptomCodes, $getIdValue);

                if (empty($perbedaan)) {
                    $addictionScore++;
                    $getSymptomName[] = $rule['symptom_name'];
                    // break;
                }
            }

            $getAddictionLevel = AddictionLevel::all();
            $shortAddictionalLevel = collect($getAddictionLevel)->sortByDesc('end_score')->all();


            $getIdAddictionLevel = 0;
            foreach ($shortAddictionalLevel as $addiction) {

                if ($addictionScore >= $addiction->start_score && $addictionScore <= $addiction->end_score) {
                    $getIdAddictionLevel = $addiction->id;
                    break;
                }
            }



            $getAddictionById = AddictionLevel::find($getIdAddictionLevel);
            $updateStatusAnswer = User::find($user->id);
            $updateStatusAnswer->answer_status = 1;
            $updateStatusAnswer->answer_result = $getAddictionById->level;
            $updateStatusAnswer->save();


            $analyseresult = new AnalysisResult();
            $analyseresult->user_id = $user->id;
            $analyseresult->addiction_level_id = $getIdAddictionLevel;
            $analyseresult->symptom_name =  json_encode($getSymptomName);
            $analyseresult->cf_score = $cfScore;
            $analyseresult->addiction_score = $addictionScore;
            $analyseresult->rules_value_id = json_encode($getIdValue);

            // ========================= PERHITUNGAN RATA-RATA CF 1 (CF BARU) ===========================
            $cfAverage1 = ($cfScore / 14) * 1;
            // ===================== SELESAI PERHITUNGAN RATA-RATA CF 1 (CF BARU) =======================

            // ======================= AMBIL NILAI CF BARU ============================
            $cfAverage2 = $getAddictionById->cf_addiction_value;
            // ================== SELESAI AMBIL NILAI CF BARU =========================

            // ======================= CARI NILAI CF AKHIR ==============================
            $cfLast = ($cfAverage1 +  $cfAverage2) / 2;
            // ================== SELESAI CARI NILAI CF AKHIR =========================


            $analyseresult->cf_average_1 = $cfAverage1;
            $analyseresult->cf_average_2 = $cfAverage2;
            $analyseresult->cf_last = $cfLast;
            $analyseresult->save();




            // return response()->json([
            //     'cf_score' => $cfScore,
            //     'get_value_id' => $getIdValue,
            //     'score_addiction' => $addictionScore,
            //     'last_symptom_code' => array_map('intval', $symptomCodes),
            //     'getIdAddictionLevel' => $getIdAddictionLevel,
            //     'getSymptomName' => $getSymptomName,
            // ]);

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

    public function print($id)
    {

        $user = Auth::user();
        $analysisResult = AnalysisResult::with('addictionLevel')->where('user_id', $user->id)->first();
        $symptomName = [];
        if (isset($analysisResult->symptom_name)) {
            $symptomName = json_decode($analysisResult->symptom_name);
        };

        $resultQuestion = ResultQuestion::where('user_id', $user->id)->with(['user', 'options', 'question'])->get();
        $resultQuestion = collect($resultQuestion)->filter(function ($filter) {
            return $filter->options->option == 'Ya';
        })->values()->all();

        // return $resultQuestion;
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'mode' => 'utf-8',
            'orientation' => 'P',
            'margin_left' => 3,
            'margin_top' => 3,
            'margin_right' => 3,
            'margin_bottom' => 3,
        ]);
        // return $analysisResult;
        $html = view('user.result.print', [
            'user' => $user,
            'analysis_result' => $analysisResult,
            'result_ruestion' => $resultQuestion,
            'symptom_name' => $symptomName,
        ]);
        // return $jobOrder;
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
