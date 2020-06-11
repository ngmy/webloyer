<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\GetAllRolesService;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\CreateViewModel;

class CreateController extends BaseController
{
    private $roleService;

    public function __construct(GetAllRolesService $roleService)
    {
        parent::__construct();

        $this->roleService = $roleService;
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $roles = $this->roleService->execute();

        return (new CreateViewModel($roles))->view('webloyer::users.create');
    }
}
