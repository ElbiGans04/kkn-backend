<?php

namespace App\Http\Requests;

use App\Enums\JenisKelompok;
use App\Enums\TipeAkun;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        $user = $request->user();
        return $user['tipe_akun'] == TipeAkun::mahasiswa->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "anggota"    => "required|array|max:5|min:1",
            "anggota.*"  => "required|numeric",
            "lokasi_kkn" => 'required|string',
            "jenis" => ['required', Rule::enum(JenisKelompok::class)],
        ];
    }
}
