<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
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
            $skor = 0;
            $getIdValue = [];
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
                            $skor++;
                            // break;
                        }
                    }
                }

                if ($newQuestion->result === 1) {
                    $getIdValue[] = $question['id'];
                }
            }
            $getRules = Rules::all();
            $scoreFix = 0;
            foreach ($getRules as $rule) {
                $symptomCodes = json_decode($rule['symptom_code'], true);
                $symptomCodes = array_map('intval', $symptomCodes);
                // foreach ($symptomCodes as $nilai) {
                //     if (in_array($nilai, $getIdValue)) {
                //         $ahayyy++;
                //         break;
                //     }
                // }

                $perbedaan = array_diff($symptomCodes, $getIdValue);

                if (empty($perbedaan)) {
                    $scoreFix++;
                    // break;
                }
            }

            $analyseresult = new AnalysisResult();
            $analyseresult->user_id = $user->id;
            $analyseresult->addiction_level_id = 1;
            $analyseresult->symptom_name = 1;
            $analyseresult->save();

            $updateStatusAnswer = User::find($user->id);
            $updateStatusAnswer->answer_status = 1;
            $updateStatusAnswer->answer_result = 'Oke siap';
            $updateStatusAnswer->save();

            return response()->json([
                'cf_score' => $skor,
                'get_value_id' => $getIdValue,
                'score_addiction' => $scoreFix,
                'last_symptom_code' => array_map('intval', $symptomCodes),
            ]);

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
        // return $user;
        // $purchaseInvoice = PurchaseInvoice::with(['supplier', 'goodsInvoice.good', 'purchaseInvoiceExpenses'])->FindOrFail($id);


        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'mode' => 'utf-8',
            'orientation' => 'P',
            'margin_left' => 3,
            'margin_top' => 3,
            'margin_right' => 3,
            'margin_bottom' => 3,
        ]);
        // return $finishingItems;
        $html = view('user.result.print', [
            'user' => $user,
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
