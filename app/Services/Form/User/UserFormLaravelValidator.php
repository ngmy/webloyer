<?php
declare(strict_types=1);

namespace App\Services\Form\User;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class UserFormLaravelValidator
 * @package App\Services\Form\User
 */
class UserFormLaravelValidator extends AbstractLaravelValidator
{
    /**
     * @var array
     */
    protected array $rules = [
        'name' => 'sometimes|required',
        'password' => 'sometimes|required|min:8|confirmed',
        'role' => 'sometimes|required',
    ];

    /**
     * @return array
     */
    protected function rules()
    {
        $rules = [];
        // For role
        if (isset($this->data['role'])) {
            foreach ($this->data['role'] as $key => $val) {
                $rules["role.$key"] = 'required|exists:roles,id';
            }
        }
        // For email
        $unique = 'unique:users,email';
        if (isset($this->data['id'])) {
            $unique .= ','.$this->data['id'];
        }
        $rules['email'] = "sometimes|required|email|$unique";
        return $rules;
    }
}
