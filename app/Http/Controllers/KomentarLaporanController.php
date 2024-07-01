<?php

namespace App\Http\Controllers;

use App\Enums\TipeAkun;
use App\Http\Requests\KomentarLaporanDataRequest;
use App\Models\Kelompok;
use App\Models\KomentarLaporan;
use App\Models\Laporan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomentarLaporanController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllKomentarLaporanData()
    {
        return KomentarLaporan::with(['laporan', 'user'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetKomentarLaporanData(string $id)
    {
        $detail = KomentarLaporan::where('id_komentar_kelompok', $id)->with(['laporan', 'user'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreKomentarLaporanData(KomentarLaporanDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = Laporan::join('kelompok', 'laporan.id_kelompok', '=', 'kelompok.id_kelompok')->where(
            [
                ['laporan.id_laporan', $data['id_laporan']],
                ['laporan.id_kelompok', $data['id_kelompok']],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Laporan tidak ditemukan'
                ],
                404
            );
        }

        $user = $request->user();
        if ($user['tipe_akun'] == TipeAkun::mahasiswa->value) {
            $detailCurrentUser = Mahasiswa::where('id_user', '=', $user['id'])->first();
            
            if ($detailCurrentUser['nim'] != $kelompok['nim_ketua_kelompok']) {
                return response(
                    [
                        'message' => 'Laporan Tidak ditemukan'
                    ]
                    );
            }
        } else if ($user['tipe_akun'] == TipeAkun::dosen_pembimbing->value) {
            if ($kelompok['id_dospem'] != $user['id']) {
                return response(
                    [
                        'message' => 'Laporan Tidak ditemukan'
                    ]
                    );
            }
        }

        DB::transaction(function () use ($data, $kelompok, $request) {
            // Buat Kelompok
            $komentarBaru = new KomentarLaporan();
            $komentarBaru->judul_komentar = $data['title'];
            $komentarBaru->body_komentar = $data['body'];
            $komentarBaru->id_laporan = $kelompok['id_laporan'];
            $komentarBaru->id_user = $request->user()['id'];
            $komentarBaru->save();
        });

        return response([
            "message" => "Berhasil membuat komentar laporan"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateKomentarLaporanData($id, KomentarLaporanDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = KomentarLaporan::where(
            [
                ['id_komentar_laporan', $id],
                ['id_user', $request->user()['id']],
                ['id_laporan', $data['id_laporan']],
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
    public function DeleteKomentarLaporanData($id, Request $request)
    {
        $data = $data = $request->validate([
            'id_laporan' => ['required', 'numeric']
        ]);
        $kelompok = KomentarLaporan::where(
            [
                ['id_komentar_laporan', $id],
                ['id_user', $request->user()['id']],
                ['id_laporan', $data['id_laporan']],
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
