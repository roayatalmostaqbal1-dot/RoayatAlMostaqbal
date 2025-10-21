<?php

namespace App\Http\Requests\Api\V1\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('super-admin')|| Auth::user()->hasPermissionTo('create-user') ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:4'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'email.unique' => 'The email has already been existed.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email must not be greater than 255 characters.',
            'name.min' => 'The name must be at least 4 characters.',
            'name.string' => 'The name must be a string.',
            'role.exists' => 'The selected role is invalid.',
            'role.string' => 'The role must be a string.',

        ];
    }
}
