<?php

namespace admin\admin_role_permissions\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
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
                'max:50',
                'unique:permissions,name',
                'regex:/^(?:[A-Za-z]+(?: [A-Za-z]+)*){3,}$/'
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'name.regex' => 'The name must contain at least 3 alphabetic characters and may only contain letters and single spaces.',
        ];
    }
}
