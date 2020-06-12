<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;

class ShowViewModel extends ViewModel
{
    /** @var object */
    private $user;

    /**
     * @param object $user
     * @return void
     */
    public function __construct(object $user)
    {
        $this->user = $user;
    }

    /**
     * @return object
     */
    public function user(): object
    {
        return $this->user;
    }
}
