<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\CreateViewModel;

class CreateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return (new CreateViewModel())->view('webloyer::users.create');
    }
}
