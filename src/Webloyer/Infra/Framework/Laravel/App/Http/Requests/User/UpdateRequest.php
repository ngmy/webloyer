<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, string|list<string>>
     */
    public function rules(): array
    {
        /** @var \Webloyer\Infra\Persistence\Eloquent\Models\User $user */
        $user = $this->route('user');

        return [
            'name'  => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email' . ',' . $user->id],
        ];
    }
}
