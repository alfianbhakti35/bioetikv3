<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function getall()
    {
        $userID = Auth::user()->id;

        $user = Auth::user();


        $assessment = Assessment::where('user_id', $userID)->get();

        if ($assessment) {
            return ResponseFormatter::success([
                'assessment' => $user->assessments
            ], 'Data berhasil didapatkan');
        }

        return ResponseFormatter::error([
            'data' => 'null'
        ], 'Data tidak ditemukan', 204);
    }

    // post data assessment ke server
    public function store(Request $request)
    {
        try {
            $assessment = Assessment::create([
                'user_id' => Auth::user()->id,
                'name_assessment' => $request->name_assessment,
                'skor' => $request->skor,
                'keteranagan' => $request->keteranagan
            ]);

            return ResponseFormatter::success([
                'assessment' => $assessment
            ], 'Assessment berhasil disimpan');
        } catch (Exception $error) {
            ResponseFormatter::error([
                'massage' => 'Error'
            ], 'Gagal menyimpan data assessment', 500);
        }
    }
}
