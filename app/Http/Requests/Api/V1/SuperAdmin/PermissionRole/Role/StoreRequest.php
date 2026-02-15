<?php

namespace App\Http\Requests\Api\V1\SuperAdmin\PermissionRole\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  Auth::user()->hasRole('super admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:roles'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Role name is required',
            'name.unique' => 'This role name already exists',
        ];
    }
}
