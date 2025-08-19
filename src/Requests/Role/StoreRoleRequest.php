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
        $name = trim($this->input('name'));

        if ($name) {
            // Convert to lowercase first, then capitalize each word
            $name = ucwords(strtolower($name));
        }

        $this->merge([
            'name' => $name,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:roles,name',
                'regex:/^[A-Za-z]+(?: [A-Za-z]+)*$/', // only letters + single spaces
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The role name is required.',
            'name.string'   => 'The role name must be a valid string.',
            'name.min'      => 'The role name must be at least 3 characters.',
            'name.max'      => 'The role name may not be greater than 50 characters.',
            'name.unique'   => 'This role name already exists.',
            'name.regex'    => 'The role name may only contain letters and single spaces.',
        ];
    }
}
