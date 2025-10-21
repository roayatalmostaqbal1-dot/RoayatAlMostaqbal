<?php
    namespace App\Http\Requests\Api\V1\SuperAdmin\PermissionRole\PermissionRole;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\Auth;

    class RemovepermissionToRoleRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('super-admin');
    }

    public function rules(): array
    {
        return [

            'role_id' => ['required', 'exists:roles,id'],
            'permission_id' => ['required', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'Role is required',
            'role_id.exists' => 'Selected role does not exist',
            'permission_id.required' => 'Permission is required',
            'permission_id.exists' => 'Selected permission does not exist',
        ];
    }
}
