<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MahasiswaDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nim' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string'],    
            'nomor_telephone' => ['required', 'string'],
            'id_gender' => ['required', 'integer'],
            'id_kelas' => ['required', 'integer'],
            'id_prodi' => ['required', 'integer'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
