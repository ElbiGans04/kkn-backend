<?php

namespace App\Http\Controllers;

use App\Enums\StatusPersetujuan;
use App\Http\Requests\AnggotaDataRequest;
use App\Models\Anggota;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllAnggotaData()
    {
        return Kelompok::with(['anggota'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetAnggotaData(string $id)
    {
        $detail = Kelompok::where('id_kelompok', $id)->with(['anggota'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreAnggotaData(AnggotaDataRequest $request)
    {
        $data = $request->validated();
        $detailCurrentUser = Mahasiswa::where('id_user', '=', $request->user()['id'])->first();
        $alreadyRegister = Anggota::whereIn('nim_mahasiswa', $data['anggota'])->join('kelompok', 'anggota.id_kelompok', '=', 'kelompok.id_kelompok')->first();
        $isLeader = Kelompok::whereIn('nim_ketua_kelompok', [...$data['anggota'], $detailCurrentUser['nim']])->first();

        $isAlreadyBeLeader = (isset($isLeader) && $isLeader['approve'] != StatusPersetujuan::reject->value);
        if ((isset($alreadyRegister) && $alreadyRegister['approve'] != StatusPersetujuan::reject->value) || $isAlreadyBeLeader) {
            return response(
                [
                    "message" => $isAlreadyBeLeader ? "Tidak dapat menjadi ketua didalam 2 kelompok yang berbeda" : "Ada 1 atau lebih mahasiswa ada yang sudah terdaftar didalam kelompok lain"
                ],
                400
            );
        }

        for ($index = 0; $index < count($data['anggota']); $index++) {
            $currentMahasiswaNim = $data['anggota'][$index];
            $isValid = Mahasiswa::where('nim', '=', $currentMahasiswaNim)->first();
            if (!isset($isValid)) {
                return response([
                    "message" => "Mahasiswa dengan nim $currentMahasiswaNim tidak terdaftar didalam sistem"
                ], 400);
            };
        }

        DB::transaction(function () use ($data, $detailCurrentUser) {
            // Buat Kelompok
            $kelompokBaru = new Kelompok();
            $kelompokBaru->nim_ketua_kelompok = $detailCurrentUser['nim'];
            $kelompokBaru->save();

            for ($index = 0; $index < count($data['anggota']); $index++) {
                $currentMahasiswaNim = $data['anggota'][$index];
                $anggota = new Anggota();
                $anggota->nim_mahasiswa = $currentMahasiswaNim;
                $anggota->id_kelompok = $kelompokBaru->id_kelompok;
                $anggota->save();
            }
        });

        return response([
            "message" => "berhasil membuat kelompok baru"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateAnggotaData($id, AnggotaDataRequest $request)
    {
        $data = $request->validated();
        $isExist = Kelompok::find($id);

        if (!isset($isExist)) {
            return response(
                ['message' => 'Kelompok tidak ditemukan'],
                404
            );
        }

        $alreadyRegister = Anggota::with(['kelompok'])->whereIn('nim_mahasiswa', $data['anggota'])->whereNot('id_kelompok', $id)->first();

        if (isset($alreadyRegister) && $alreadyRegister['approve'] != StatusPersetujuan::reject->value) {
            return response(
                [
                    "message" => "Ada 1 atau lebih mahasiswa ada yang sudah terdaftar didalam kelompok lain"
                ],
                400
            );
        }

        for ($index = 0; $index < count($data['anggota']); $index++) {
            $currentMahasiswaNim = $data['anggota'][$index];
            $isValid = Mahasiswa::where('nim', '=', $currentMahasiswaNim)->first();
            if (!isset($isValid)) {
                return response([
                    "message" => "Mahasiswa dengan nim $currentMahasiswaNim tidak terdaftar didalam sistem"
                ], 400);
            };
        }


        DB::transaction(function () use ($data, $id) {
            // Hapus Member Lama
            for ($index = 0; $index < count($data['anggota']); $index++) {
                $currentMahasiswaNim = $data['anggota'][$index];
                $isValid = Anggota::where('nim_mahasiswa', $currentMahasiswaNim)->where('id_kelompok', $id)->first();
                $isValid->delete();
            }

            for ($index = 0; $index < count($data['anggota']); $index++) {
                $currentMahasiswaNim = $data['anggota'][$index];
                $anggota = new Anggota();
                $anggota->nim_mahasiswa = $currentMahasiswaNim;
                $anggota->id_kelompok = $id;
                $anggota->save();
            }
        });



        return response(
            [
                "message" => "berhasil memperbaharui data"
            ],
            200
        );
    }

    /**
     * Delete Data to database
     */
    public function DeleteAnggotaData($id)
    {
        $isExist = Kelompok::find($id);

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }

        
        DB::transaction(function () use ($isExist, $id) {
            Anggota::where('id_kelompok', $id)->delete();
            $isExist->delete();
        });

        return response(
            ['message' => 'Data berhasil di hapus'],
            200
        );
    }
}
