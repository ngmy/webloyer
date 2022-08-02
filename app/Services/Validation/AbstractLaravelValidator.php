<?php
declare(strict_types=1);

namespace App\Services\Validation;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory;

/**
 * Class AbstractLaravelValidator
 * @package App\Services\Validation
 */
abstract class AbstractLaravelValidator implements ValidableInterface
{
    /**
     * @var Factory
     */
    protected Factory $validator;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var MessageBag
     */
    protected ?MessageBag $errors;

    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * Create a new validator instance.
     *
     * @param Factory $validator
     * @return void
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Add data to validation.
     *
     * @param array Data to validation
     * @return ValidableInterface $this
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Test whether passes validation.
     *
     * @return boolean
     */
    public function passes()
    {
        $rules = array_merge($this->rules, $this->rules());
        $validator = $this->validator->make($this->data, $rules);
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return false;
        }
        return true;
    }

    /**
     * @return MessageBag|null
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Return validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [];
    }
}
