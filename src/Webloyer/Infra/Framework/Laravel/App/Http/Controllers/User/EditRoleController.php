<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Common\App\Service\ApplicationService;
use Webloyer\App\Service\User\{
    GetAllRolesService,
    GetUserRequest,
};
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\EditRoleViewModel;

class EditRoleController extends BaseController
{
    private $roleService;

    public function __construct(
        ApplicationService $service,
        GetAllRolesService $roleService
    ) {
        parent::__construct($service);

        $this->roleService = $roleService;
    }

    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new GetUserRequest())->setId($id);
        $user = $this->service->execute($serviceRequest);

        $roles = $this->roleService->execute();

        return (new EditRoleViewModel($user, $roles))->view('webloyer::users.edit_role');
    }
}
