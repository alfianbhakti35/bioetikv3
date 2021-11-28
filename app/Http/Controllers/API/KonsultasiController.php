<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function buatJadwal(Request $request)
    {
        try {
            $mahasiswaID = Auth::user()->id;

            $konsultasi = Konsultasi::where([
                ['mahasiswa_id', '=', $mahasiswaID],
                ['status', '=', 'PENDING'],
            ])->first();

            if ($konsultasi !== null) {
                return ResponseFormatter::error([
                    'message' => 'Anda masih memiliki konsultasi masih pending'
                ], 'Gagal membuat jadwal konsultasi', 500);
            }

            // Create data
            $data = Konsultasi::create([
                'mahasiswa_id' => Auth::user()->id,
                'dosen_id' => $request->dosen_id,
                'jadwal' => $request->jadwal
            ]);

            // Response untuk jadwal berhasil dibuat
            return ResponseFormatter::success([
                'message' => 'Jadwal berhasil dibuat',
                'data' => $data
            ], 'Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Gagal membuat jadwal', 500);
        }
    }

    // Konsultasi diterima
    public function acceptKonsultasi(Request $request)
    {
        try {
            Konsultasi::where('id', $request->id)->update([
                'status' => "DITERIMA"
            ]);

            return ResponseFormatter::success([
                'message' => 'Berhasil diupdate menjadi diterima'
            ], 'Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => $error
            ], 'Gagal', 500);
        }
    }

    // Konfirmasi selesai
    public function konsultasiSelesai(Request $request)
    {
        try {
            if ($request->rekam_medik === 'ada') {
                Konsultasi::where('id', $request->id)->update([
                    'status' => "PENYERAHAN REKAM MEDIK KE ADMIN",
                    'rekam_medik' => true
                ]);
            }

            Konsultasi::where('id', $request->id)->update([
                'status' => "SELESAI"
            ]);

            return ResponseFormatter::success([
                'message' => 'Konfirmasi Konsultasi selesai berhasil'
            ], 'Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => $error
            ], 'Gagal', 500);
        }
    }
}
