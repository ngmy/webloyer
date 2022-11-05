<?php
declare(strict_types=1);

namespace App\Services\Validation;

/**
 * Interface ValidableInterface
 * @package App\Services\Validation
 */
interface ValidableInterface
{
    /**
     * Add data to validation.
     *
     * @param array Data to validation
     * @return ValidableInterface $this
     */
    public function with(array $input);

    /**
     * Test whether passes validation.
     *
     * @return boolean
     */
    public function passes();

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function errors();
}
