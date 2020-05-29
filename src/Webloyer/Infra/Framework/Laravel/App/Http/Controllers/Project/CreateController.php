<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Webloyer\App\Service\Recipe\GetRecipesService;
use Webloyer\App\Service\Server\GetServersService;
use Webloyer\App\Service\User\GetUsersService;

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

        return view('webloyer::projects.create')
            ->with('recipes', array_column($recipes, 'name', 'id')) // TODO view model
            ->with('servers', array_column($servers, 'name', 'id')) // TODO view model
            ->with('users', ['' => ''] + array_column($users, 'email', 'id')); // TODO view model
    }
}
