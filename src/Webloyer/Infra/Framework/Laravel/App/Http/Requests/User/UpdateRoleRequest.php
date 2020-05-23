<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        $rules = [
            'role' => ['required', 'array'],
        ];

        if (!empty($this->role)) {
            foreach (array_keys($this->role) as $index) {
                $rules['role.' . $index] = ['required', 'exists:roles,id'];
            }
        }

        return $rules;
    }
}
