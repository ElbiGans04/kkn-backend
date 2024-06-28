<?php

namespace App\Http\Controllers;

use App\Http\Requests\KomentarKelompokDataRequest;
use App\Http\Requests\KomentarProgramKerjaDataRequest;
use App\Models\Kelompok;
use App\Models\KomentarKelompok;
use App\Models\KomentarProgramKerja;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomentarProgramKerjaController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllKomentarProgramKerjaData()
    {
        return KomentarProgramKerja::with(['dosen_pembimbing', 'program_kerja'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetKomentarProgramKerjaData(string $id)
    {
        $detail = KomentarProgramKerja::where('id_komentar_proker', $id)->with(['dosen_pembimbing', 'program_kerja'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreKomentarProgramKerjaData(KomentarProgramKerjaDataRequest $request)
    {
        $data = $request->validated();
        $proker = ProgramKerja::join('kelompok', 'program_kerja.id_kelompok', '=', 'kelompok.id_kelompok')->where(
            [
                ['program_kerja.id_kelompok', $data['id_kelompok']],
                ['program_kerja.id_proker', $data['id_proker']],
                ['kelompok.id_dospem', $request->user()['id']]
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($proker)) {
            return response(
                [
                    'message' => 'Program Kerja tidak ditemukan'
                ],
                404
            );
        }


        DB::transaction(function () use ($data, $proker, $request) {
            // Buat proker
            $komentarBaru = new KomentarProgramKerja();
            $komentarBaru->judul_komentar = $data['title'];
            $komentarBaru->body_komentar = $data['body'];
            $komentarBaru->id_proker = $proker['id_proker'];
            $komentarBaru->id_dospem = $request->user()['id'];
            $komentarBaru->save();
        });

        return response([
            "message" => "Berhasil membuat komentar program kerja"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateKomentarProgramKerjaData($id, KomentarProgramKerjaDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = KomentarProgramKerja::where(
            [
                ['id_komentar_proker', $id],
                ['id_dospem', $request->user()['id']],
                ['id_proker', $data['id_proker']],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Data tidak ditemukan'
                ],
                404
            );
        }


        DB::transaction(function () use ($data, $kelompok) {
            $kelompok['judul_komentar'] = $data['title'];
            $kelompok['body_komentar'] = $data['body'];
            $kelompok->save();
        });



        return response(
            [
                "message" => "Berhasil memperbaharui data"
            ],
            200
        );
    }

    /**
     * Delete Data to database
     */
    public function DeleteKomentarProgramKerjaData($id, Request $request)
    {
        $data = $data = $request->validate([
            'id_proker' => ['required', 'numeric']
        ]);
        $kelompok = KomentarProgramKerja::where(
            [
                ['id_komentar_proker', $id],
                ['id_dospem', $request->user()['id']],
                ['id_proker', $data['id_proker']],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Data tidak ditemukan'
                ],
                404
            );
        }


        DB::transaction(function () use ($kelompok) {
            $kelompok->delete();
        });

        return response(
            ['message' => 'Data berhasil di hapus'],
            200
        );
    }
}
