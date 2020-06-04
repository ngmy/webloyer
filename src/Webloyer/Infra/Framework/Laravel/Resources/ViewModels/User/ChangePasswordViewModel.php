<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;

class ChangePasswordViewModel extends ViewModel
{
    private $user;

    public function __construct(object $user)
    {
        $this->user = $user;
    }

    public function user(): object
    {
        return $this->user;
    }
}
