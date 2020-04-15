<?php

declare(strict_types=1);

namespace App\Http\Requests\Setting\Mail;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'driver'          => ['required', 'in:smtp,mail,sendmail'],
            'from_address'    => ['required', 'email'],
            'from_name'       => ['nullable', 'string'],
            'smtp_host'       => ['nullable', 'string'],
            'smtp_port'       => ['nullable', 'integer', 'min:0', 'max:65535'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl'],
            'smtp_username'   => ['nullable', 'string'],
            'smtp_password'   => ['nullable', 'string'],
            'sendmail_path'   => ['nullable', 'string'],
        ];
    }
}
