<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Spatie\ViewModels\ViewModel;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe\CreateViewModel;

class CreateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return ViewModel
     */
    public function __invoke(): ViewModel
    {
        return (new CreateViewModel())->view('webloyer::recipe.create');
    }
}
