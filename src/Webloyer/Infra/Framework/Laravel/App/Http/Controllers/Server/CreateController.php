<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server\CreateViewModel;

class CreateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return (new CreateViewModel())->view('webloyer::servers.create');
    }
}
