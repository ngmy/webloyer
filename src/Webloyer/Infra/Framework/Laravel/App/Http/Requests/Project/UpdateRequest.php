<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Requests\Project;

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
        $rules = [
            'name'                              => ['required'],
            'stage'                             => ['required'],
            'recipe_id'                         => ['required', 'array', 'min:1'],
            'server_id'                         => ['required', 'string', 'exists:servers,uuid'],
            'repository'                        => ['required', 'url'],
            'deploy_path'                       => ['nullable', 'string'],
            'email_notification_recipient'      => ['nullable', 'email'],
            'days_to_keep_deployments'          => ['nullable', 'integer', 'min:1'],
            'max_number_of_deployments_to_keep' => ['nullable', 'integer', 'min:1'],
            'keep_last_deployment'              => ['nullable', 'boolean'],
            'github_webhook_secret'             => ['nullable', 'string'],
            'github_webhook_user_id'            => ['nullable', 'string', 'exists:users,uuid'],
        ];

        if (!empty($this->recipe_id)) {
            foreach (array_keys($this->recipe_id) as $index) {
                $rules['recipe_id.' . $index] = ['required', 'string', 'exists:recipes,uuid'];
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
            'days_to_keep_deployments' => $this->days_to_keep_deployments ? (int) $this->days_to_keep_deployments : null,
            'keep_last_deployment' => (bool) $this->keep_last_deployment,
            'max_number_of_deployments_to_keep' => $this->max_number_of_deployments_to_keep ? (int) $this->max_number_of_deployments_to_keep : null,
            'recipe_id' => $this->recipe_id_order ? explode(',', $this->recipe_id_order) : [],
        ]);
    }
}
