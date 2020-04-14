<?php

declare(strict_types=1);

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name'                              => ['required'],
            'stage'                             => ['required'],
            'recipe_id'                         => ['required', 'array', 'min:1'],
            'server_id'                         => ['required', 'exists:servers,id'],
            'repository'                        => ['required', 'url'],
            'deploy_path'                       => ['nullable', 'string'],
            'email_notification_recipient'      => ['nullable', 'email'],
            'days_to_keep_deployments'          => ['nullable', 'integer', 'min:1'],
            'max_number_of_deployments_to_keep' => ['nullable', 'integer', 'min:1'],
            'keep_last_deployment'              => ['nullable', 'boolean'],
            'github_webhook_secret'             => ['nullable', 'string'],
        ];

        if (!empty($this->recipe_id)) {
            foreach (array_keys($this->recipe_id) as $index) {
                $rules['recipe_id.' . $index] = ['required', 'exists:recipes,id'];
            }
        }

        return $rules;
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'recipe_id' => explode(',', $this->recipe_id_order),
        ]);
    }
}
