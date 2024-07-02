<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AkademikDataRequest extends FormRequest
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
            "sks" => ['required', 'integer'],
            "semester_saat_ini" => ['required', 'integer'],
            "ipk" => ['required', 'integer'],
            "nim_mahasiswa" => ['required', 'string'],
        ];
    }
}
