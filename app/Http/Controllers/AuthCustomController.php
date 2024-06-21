<?php

namespace App\Http\Controllers;

use App\Enums\TipeAkun;
use App\Http\Requests\AuthDataRequest;
use App\Models\Anggota;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthCustomController extends Controller
{
    function login(AuthDataRequest $authRequest)
    {
        $data = $authRequest->validated();

        if ($data['type_account'] == TipeAkun::mahasiswa->value) {
            $mahasiswa = Mahasiswa::where('nim', $data['id'])->first();
            $user = null;

            if (isset($mahasiswa)) {
                $user = User::all()->where('id', '=', $mahasiswa['id_user'])->where('tipe_akun', '=', TipeAkun::mahasiswa->value)->first();
            }

            // Jika akun tidak ditemukan
            if (!isset($mahasiswa) || !isset($user)) {
                return [
                    "message" => "Akun Tidak Ditemukan",
                ];
            }

            // Sudah Terdaftar Sebagai anggota
            $alreadyRegisterAsMember = Anggota::where('nim_mahasiswa', '=', $data['id'])->first();
            if (isset($alreadyRegisterAsMember)) {
                return [
                    "message" => "Akun bukan ketua kelompok",
                ];
            }

            return [
                'token' => $user->createToken('kkn-app')->plainTextToken,
            ];
        }


        $dosen = Dosen::where('nid', $data['id'])->first();
        $user = null;

        if (isset($dosen)) {
            $user = User::all()->where('id', '=', $dosen['id_user'])->where('tipe_akun', '=', TipeAkun::dosen_pembimbing->value)->first();
        }

        // Jika akun tidak ditemukan
        if (!isset($dosen) || !isset($user)) {
            return [
                "message" => "Akun Tidak Ditemukan",
            ];
        }
        
        return [
            'token' => $user->createToken('kkn-app')->plainTextToken,
        ];
    }

    function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            "message" => "SUCCESS LOGOUT",
        ];
    }

    function getUserDetail(Request $request)
    {
        $data = $request->user();
        
        switch ($data['tipe_akun']) {
            case TipeAkun::mahasiswa->value : {
                $detail = Mahasiswa::where('id_user', '=', $data['id'])->first();
                break;
            }

            default : {
                $detail = Dosen::where('id_user', '=', $data['id'])->first();
                break;
            }
        }
        
        return response(
            [
                "data" => [
                    "summary" => $data,
                    "detail" => $detail,
                ]
            ]
        );
    }

    function loginGet () {
        return response(
            [
                "message" => "Have To Login First"
            ],
            403
        );
    }
}
