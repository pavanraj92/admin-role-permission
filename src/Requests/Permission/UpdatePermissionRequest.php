<?php

namespace admin\admin_role_permissions\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
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
                Rule::unique('permissions', 'name')->ignore($this->permission),
                'regex:/^(?:[A-Za-z]+(?: [A-Za-z]+)*){3,}$/'
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The permission name is required.',
            'name.string'   => 'The permission name must be a valid string.',
            'name.min'      => 'The permission name must be at least 3 characters.',
            'name.max'      => 'The permission name may not be greater than 50 characters.',
            'name.unique'   => 'This permission name already exists.',
            'name.regex'    => 'The permission name may only contain letters and spaces.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => ucwords(strtolower(trim($this->name)))
            ]);
        }
    }
}
