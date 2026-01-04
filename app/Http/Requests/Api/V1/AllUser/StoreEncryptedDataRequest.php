<?php

namespace App\Http\Requests\Api\V1\AllUser;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreEncryptedDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'encrypted_dek' => 'required|string',
            'encrypted_dek_server' => 'nullable|string',
            'encrypted_dek_recovery' => 'nullable|string',
            'dek_salt' => 'required|string',
            'dek_nonce' => 'required|string',
            'dek_salt_recovery' => 'nullable|string',
            'dek_nonce_recovery' => 'nullable|string',
            'profile_ciphertext' => 'required|string',
            'profile_nonce' => 'required|string',
            'data_type' => 'nullable|string|max:100',
            'metadata' => 'nullable|string',
        ];
    }
}
