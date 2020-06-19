<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels;

trait ViewModelHelpers
{
    /**
     * @param bool $value
     * @return string
     */
    public function yesOrNo(bool $value): string
    {
        return $value ? 'yes' : 'no';
    }

    /**
     * @param string|null $value
     * @return string
     */
    public function hyphenIfBlank(?string $value): string
    {
        return empty($value) ? '-' : $value;
    }
}
