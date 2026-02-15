<?php

namespace App\Http\Requests\Api\V1\SuperAdmin\OAuth2Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('super admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'redirect' => ['required', 'url'],
            'confidential' => ['required', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Application name is required',
            'name.string' => 'Application name must be a string',
            'name.max' => 'Application name cannot exceed 255 characters',
            'redirect.required' => 'Redirect URI is required',
            'redirect.url' => 'Redirect URI must be a valid URL',
            'confidential.required' => 'Confidential flag is required',
            'confidential.boolean' => 'Confidential flag must be true or false',
        ];
    }
}

