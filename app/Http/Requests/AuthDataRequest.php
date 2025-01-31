<?php

namespace App\Http\Requests;

use App\Enums\TipeAkun;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthDataRequest extends FormRequest
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
            'id' => ['required', 'numeric'],
            'password' => ['required', 'max:100'],
            'type_account' => ['required', Rule::enum(TipeAkun::class)],
        ];
    }
}
