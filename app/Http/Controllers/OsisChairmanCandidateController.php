<?php

namespace App\Http\Controllers;

use App\Models\OsisChairmanCandidate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OsisChairmanCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $osisCandidate = OsisChairmanCandidate::all();
        // return $osisCandidate;
        return view('admin.osis-candidate.index', [
            'osis_candidate' => $osisCandidate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.osis-candidate.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $newOsisCandidate = new OsisChairmanCandidate();
            $newOsisCandidate->name = $request->name;
            $newOsisCandidate->class = $request->class;
            $newOsisCandidate->visi = $request->visi;
            $newOsisCandidate->misi = $request->misi;
            $newOsisCandidate->sequence_number = $request->sequence_number;


            if ($request->image != null) {
                $fileImage = $request->file('image');
                $file_image_name =  $request->name . "_" . $request->image_name;
                $fileImage->move('file/image', $file_image_name);
                $newOsisCandidate->image = $file_image_name;
            }
            $newOsisCandidate->save();


            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $newOsisCandidate,
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
        $osisCandidate = OsisChairmanCandidate::findOrFail($id);
        // return $addictionLevel;
        return view('admin.osis-candidate.edit', [
            'osis_candidate' => $osisCandidate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $newOsisCandidate = OsisChairmanCandidate::findOrFail($id);
            $newOsisCandidate->name = $request->name;
            $newOsisCandidate->class = $request->class;
            $newOsisCandidate->visi = $request->visi;
            $newOsisCandidate->misi = $request->misi;
            $newOsisCandidate->sequence_number = $request->sequence_number;

            if ($request->image != null) {
                $fileImage = $request->file('image');
                $file_image_name =  $request->name . "_" . $request->image_name;
                $fileImage->move('file/image', $file_image_name);
                $newOsisCandidate->image = $file_image_name;
            }
            $newOsisCandidate->save();


            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'code' => 200,
                'error' => false,
                'data' => $newOsisCandidate,
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
            $addiction = OsisChairmanCandidate::find($id);
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
