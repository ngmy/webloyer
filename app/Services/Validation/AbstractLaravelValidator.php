<?php namespace App\Services\Validation;

use Illuminate\Validation\Factory;

abstract class AbstractLaravelValidator implements ValidableInterface {

	protected $validator;

	protected $data = [];

	protected $errors = [];

	protected $rules = [];

	/**
	 * Create a new validator instance.
	 *
	 * @param \Illuminate\Validation\Factory $validator
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
	 * @return \App\Services\Validation\ValidableInterface $this
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
	 * Return validation errors.
	 *
	 * @return array
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
