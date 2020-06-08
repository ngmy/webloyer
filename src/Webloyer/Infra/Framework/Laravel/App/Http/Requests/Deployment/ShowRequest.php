<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Requests\Deployment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ShowRequest extends FormRequest
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
        return [
            'format' => ['nullable', 'string', 'in:html,json'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($validator->errors()->has('format')) {
            abort(400, (string) $validator->errors());
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
