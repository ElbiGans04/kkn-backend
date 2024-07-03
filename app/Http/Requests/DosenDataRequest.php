<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DosenDataRequest extends FormRequest
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
            'nid' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string'],
            'id_gender' => ['required', 'integer'],
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
}
