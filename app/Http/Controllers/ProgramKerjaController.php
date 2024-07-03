<?php

namespace App\Http\Controllers;

use App\Enums\StatusPersetujuan;
use App\Http\Requests\AnggotaDataRequest;
use App\Http\Requests\ProgramKerjaDataRequest;
use App\Models\Anggota;
use App\Models\Dosen;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProgramKerjaController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllProgramKerjaData()
    {
        return ProgramKerja::with(['kelompok'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetProgramKerjaData(string $id)
    {
        $detail = ProgramKerja::where('id_proker', $id)->with(['kelompok'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreProgramKerjaData(ProgramKerjaDataRequest $request)
    {
        Gate::authorize('allow', ProgramKerja::class);
        $data = $request->validated();
        $detailCurrentUser = Mahasiswa::where('id_user', '=', $request->user()['id'])->first();
        // Hanya mencari kelompok yang sudah disetujui
        $kelompok = Kelompok::where([
            ['nim_ketua_kelompok', $detailCurrentUser['nim']],
            ['approve', StatusPersetujuan::approve->value]
        ])->first();
        if (!isset($kelompok)) {
            return response(
                [
                    "message" => "Kelompok tidak ditemukan"
                ],
                404
            );
        }

        DB::transaction(function () use ($data, $detailCurrentUser, $kelompok) {
            $proker = new ProgramKerja();
            $proker->judul_proker = $data['title'];
            $proker->body_proker = $data['body'];
            $proker->id_kelompok = $kelompok['id_kelompok'];
            $proker->save();
        });

        return response([
            "message" => "Berhasil membuat program kerja baru"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateProgramKerjaData($id, ProgramKerjaDataRequest $request)
    {
        $data = $request->validated();
        $proker = ProgramKerja::find($id);

        if (!isset($proker)) {
            return response(
                ['message' => 'Program Kerja tidak ditemukan'],
                404
            );
        }

        DB::transaction(function () use ($data, $proker) {
            $proker->judul_proker = $data['title'];
            $proker->body_proker = $data['body'];
            $proker->save();
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
    public function DeleteProgramKerjaData($id)
    {
        $proker = ProgramKerja::find($id);

        if (!isset($proker)) {
            return response(
                ['message' => 'Program Kerja tidak ditemukan'],
                404
            );
        }

        DB::transaction(function () use ($proker) {
            $proker->delete();
        });

        return response(
            ['message' => 'Data berhasil di hapus'],
            200
        );
    }

    /**
     * Update Data to database
     */
    public function UpdateProgramKerjaStatus($id, Request $request)
    {
        $isExist = ProgramKerja::find($id);
        $data = $request->validate([
            'status' => ['required', Rule::enum(StatusPersetujuan::class)]
        ]);

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }


        DB::transaction(function () use ($isExist, $data) {
            $isExist->approve = $data['status'];
            $isExist->save();
        });

        return response(
            ['message' => 'Data berhasil di perbaharui'],
            200
        );
    }
}
