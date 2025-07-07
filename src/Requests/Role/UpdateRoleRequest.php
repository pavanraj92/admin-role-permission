<?php

namespace admin\admin_role_permissions\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:15',
                'unique:roles,name,' . $this->route('role')->id,
                'regex:/^(?:[A-Za-z]+(?: [A-Za-z]+)*){3,}$/',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'name.regex' => 'The name must contain at least 3 alphabetic characters and may only contain letters and single spaces.',
        ];
    }
}
