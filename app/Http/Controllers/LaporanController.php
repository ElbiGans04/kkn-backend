<?php

namespace App\Http\Controllers;

use App\Enums\StatusPersetujuan;
use App\Http\Requests\LaporanDataRequest;
use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\Mahasiswa;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllLaporanData()
    {
        return Laporan::with(['kelompok'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetLaporanData(string $id)
    {
        $detail = Laporan::where('id_laporan', $id)->with(['kelompok'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreLaporanData(LaporanDataRequest $request)
    {
        $data = $request->validated();
        $detailCurrentUser = Mahasiswa::where('id_user', '=', $request->user()['id'])->first();
        $kelompok = Kelompok::where(
            [
                [
                    'nim_ketua_kelompok', $detailCurrentUser['nim']
                ],
                [
                    'approve', StatusPersetujuan::approve->value
                ],
            ]
        )->first();

        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Kelompok tidak ditemukan'
                ],
                404
            );
        }


        $proker = ProgramKerja::where(
            [
                ['id_kelompok', $kelompok['id_kelompok']],
                ['approve', StatusPersetujuan::approve->value],
            ]
        )->first();

        if (!isset($proker)) {
            return response(
                [
                    'message' => 'Harap Membuat Program Kerja Terlebih Dahulu'
                ],
                404
            );
        }

        DB::transaction(function () use ($data, $kelompok) {
            $laporan = new Laporan();
            $laporan->judul_laporan = $data['title'];
            $laporan->body_laporan = $data['body'];
            $laporan->id_kelompok = $kelompok['id_kelompok'];
            $laporan->approve = StatusPersetujuan::review->value;
            $laporan->save();
        });

        return response([
            "message" => "Berhasil membuat laporan baru"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateLaporanData($id, LaporanDataRequest $request)
    {
        $data = $request->validated();
        $detailCurrentUser = Mahasiswa::where('id_user', '=', $request->user()['id'])->first();
        $isExist = Laporan::join('kelompok', 'laporan.id_kelompok', '=', 'kelompok.id_kelompok')->where(
            [
                [
                    'laporan.id_laporan',
                    $id
                ],
                [
                    'kelompok.nim_ketua_kelompok',
                    $detailCurrentUser['nim']
                ],
                [
                    'kelompok.approve',
                    StatusPersetujuan::approve->value
                ],
            ]
        )->first();

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }

        DB::transaction(function () use ($data, $isExist) {
            $isExist->judul_laporan = $data['title'];
            $isExist->body_laporan = $data['body'];
            $isExist->save();
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
    public function DeleteLaporanData($id, Request $request)
    {
        $detailCurrentUser = Mahasiswa::where('id_user', '=', $request->user()['id'])->first();
        $isExist = Laporan::join('kelompok', 'laporan.id_kelompok', '=', 'kelompok.id_kelompok')->where(
            [
                [
                    'laporan.id_laporan',
                    $id
                ],
                [
                    'kelompok.nim_ketua_kelompok',
                    $detailCurrentUser['nim']
                ],
                [
                    'kelompok.approve',
                    StatusPersetujuan::approve->value
                ],
            ]
        )->first();

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }


        DB::transaction(function () use ($isExist, $id) {
            $isExist->delete();
        });

        return response(
            ['message' => 'Data berhasil di hapus'],
            200
        );
    }

    /**
     * Update Data to database
     */
    public function UpdateLaporanStatus($id, Request $request)
    {
        $detailCurrentUser = Mahasiswa::where('id_user', '=', $request->user()['id'])->first();
        $isExist = Laporan::join('kelompok', 'laporan.id_kelompok', '=', 'kelompok.id_kelompok')->where(
            [
                [
                    'laporan.id_laporan',
                    $id
                ],
                [
                    'kelompok.nim_ketua_kelompok',
                    $detailCurrentUser['nim']
                ],
                [
                    'kelompok.approve',
                    StatusPersetujuan::approve->value
                ],
            ]
        )->first();

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }

        $data = $request->validate([
            'status' => ['required', Rule::enum(StatusPersetujuan::class)]
        ]);


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
