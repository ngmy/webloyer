<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe\CreateViewModel;

class CreateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return (new CreateViewModel())->view('webloyer::recipes.create');
    }
}
