<?php

namespace App\Http\Controllers;

use App\Enums\StatusPersetujuan;
use App\Http\Requests\LaporanDataRequest;
use App\Http\Requests\SidangDataRequest;
use App\Models\Anggota;
use App\Models\Dosen;
use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\Mahasiswa;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SidangController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllSidangData()
    {
        return Sidang::with(['kelompok'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetSidangData(string $id)
    {
        $detail = Sidang::where('id_sidang', $id)->with(['kelompok'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreSidangData(SidangDataRequest $request)
    {
        $data = $request->validated();

        $kelompok = Kelompok::where(
            [
                [
                    'id_kelompok', $data['id_kelompok']
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

        $laporan = Laporan::where(
            [
                [
                    'id_kelompok', $kelompok['id_kelompok']
                ],
                [
                    'approve', StatusPersetujuan::approve->value
                ],
            ]
        )->first();

        if (!isset($laporan)) {
            return response(
                [
                    'message' => 'Tidak ada laporan yang disetujui'
                ],
                400
            );
        }

        DB::transaction(function () use ($data, $kelompok) {
            $sidang = new Sidang();
            $sidang->judul_sidang = $data['title'];
            $sidang->body_sidang = $data['body'];
            $sidang->tanggal_sidang = $data['tanggal_sidang'];
            $sidang->id_kelompok = $kelompok['id_kelompok'];
            $sidang->approve = StatusPersetujuan::review->value;
            $sidang->save();
        });

        return response([
            "message" => "Berhasil membuat sidang baru"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateSidangData($id, SidangDataRequest $request)
    {
        $data = $request->validated();
        $isExist = Sidang::where(
            [
                [
                    'id_sidang', $id
                ],
                [
                    'id_kelompok', $data['id_kelompok']
                ]
            ]
        )->first();

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }

        DB::transaction(function () use ($data, $isExist) {
            $isExist->judul_sidang = $data['title'];
            $isExist->body_sidang = $data['body'];
            $isExist->tanggal_sidang = $data['tanggal_sidang'];
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
    public function DeleteSidangData($id, Request $request)
    {
        $data = $request->validate([
            'id_kelompok' => ['required', 'integer']
        ]);

        $isExist = Sidang::where(
            [
                [
                    'id_sidang', $id
                ],
                [
                    'id_kelompok', $data['id_kelompok']
                ]
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
    public function UpdateSidangStatus($id, Request $request)
    {
        $data = $request->validate([
            'id_kelompok' => ['required', 'integer'],
            'status' => ['required', Rule::enum(StatusPersetujuan::class)]
        ]);

        $isExist = Sidang::where(
            [
                [
                    'id_sidang', $id
                ],
                [
                    'id_kelompok', $data['id_kelompok']
                ]
            ]
        )->first();

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
