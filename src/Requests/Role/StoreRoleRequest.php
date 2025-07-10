<?php

namespace admin\admin_role_permissions\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Trim inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->input('name')),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:roles,name',
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
