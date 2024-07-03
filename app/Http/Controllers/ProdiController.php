<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdiDataRequest;
use App\Models\Akademik;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProdiController extends Controller
{
     /**
     * Return All Data
     */
    public function GetAllProdiData()
    {
        return Prodi::all();
    }

    /**
     * Return Spesific Data
     */
    public function GetProdiData(string $id)
    {
        $detail = Prodi::where('id_prodi', $id)->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreProdiData(ProdiDataRequest $request)
    {
        Gate::authorize('allow', Prodi::class);
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            // Buat Kelompok
            $akademik2 = new Prodi();
            $akademik2->nama_prodi = $data['nama_prodi'];
            $akademik2->save();
        });

        return response([
            "message" => "Berhasil membuat data akademik"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateProdiData($id, ProdiDataRequest $request)
    {
        Gate::authorize('allow', Prodi::class);
        $data = $request->validated();

        $kelompok = Prodi::where(
            [
                ['id_prodi', $id],
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
            $kelompok['nama_prodi'] = $data['nama_prodi'];
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
    public function DeleteProdiData($id)
    {
        Gate::authorize('allow', Prodi::class);
        $kelompok = Prodi::where(
            [
                ['id_prodi', $id],
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
