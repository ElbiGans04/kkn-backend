<?php

namespace App\Http\Requests;

use App\Enums\TipeAkun;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class KomentarKelompokDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        $user = $request->user();
        return $user['tipe_akun'] != TipeAkun::mahasiswa->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
            'id_kelompok' => ['required', 'integer']
        ];
    }
}
