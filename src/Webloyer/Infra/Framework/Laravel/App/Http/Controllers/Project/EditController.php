<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Common\App\Service\ApplicationService;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\Service\Project\GetProjectRequest;
use Webloyer\App\Service\Recipe\GetRecipesService;
use Webloyer\App\Service\Server\GetServersService;
use Webloyer\App\Service\User\GetUsersService;
use Webloyer\Domain\Model\Project\ProjectDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project\EditViewModel;

class EditController extends BaseController
{
    /** @var GetRecipesService */
    private $recipeService;
    /** @var GetServersService */
    private $serverService;
    /** @var GetUsersService */
    private $userService;

    /**
     * @param ApplicationService $service
     * @param GetRecipesService  $recipeService
     * @param GetServersService  $serverService
     * @param GetUsersService    $userService
     * @return void
     */
    public function __construct(
        ApplicationService $service,
        GetRecipesService $recipeService,
        GetServersService $serverService,
        GetUsersService $userService
    ) {
        parent::__construct($service);

        $this->recipeService = $recipeService;
        $this->serverService = $serverService;
        $this->userService = $userService;
    }

    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return ViewModel
     */
    public function __invoke(string $id): ViewModel
    {
        $serviceRequest = (new GetProjectRequest())->setId($id);

        assert(!is_null($this->service));

        try {
            $project = $this->service->execute($serviceRequest);
        } catch (ProjectDoesNotExistException $exception) {
            abort(404);
        }

        $recipes = $this->recipeService->execute();
        $servers = $this->serverService->execute();
        $users = $this->userService->execute();

        return (new EditViewModel(
            $project,
            $recipes,
            $servers,
            $users
        ))->view('webloyer::project.edit');
    }
}
