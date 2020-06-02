<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Webloyer\App\Service\Recipe\GetRecipesService;
use Webloyer\App\Service\Server\GetServersService;
use Webloyer\App\Service\User\GetUsersService;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project\CreateViewModel;

class CreateController extends BaseController
{
    private $recipeService;
    private $serverService;
    private $userService;

    public function __construct(
        GetRecipesService $recipeService,
        GetServersService $serverService,
        GetUsersService $userService
    ) {
        parent::__construct();

        $this->recipeService = $recipeService;
        $this->serverService = $serverService;
        $this->userService = $userService;
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $recipes = $this->recipeService->execute();
        $servers = $this->serverService->execute();
        $users = $this->userService->execute();

        return (new CreateViewModel(
            $recipes,
            $servers,
            $users
        ))->view('webloyer::projects.create');
    }
}
