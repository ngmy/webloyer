<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Spatie\ViewModels\ViewModel;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server\CreateViewModel;

class CreateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return ViewModel
     */
    public function __invoke(): ViewModel
    {
        return (new CreateViewModel())->view('webloyer::servers.create');
    }
}
