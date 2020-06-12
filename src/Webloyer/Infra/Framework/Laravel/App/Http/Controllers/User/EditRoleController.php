<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Common\App\Service\ApplicationService;
use Common\ServiceBus\QueryBus;
use Webloyer\App\Service\User\GetUserRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\EditRoleViewModel;
use Webloyer\Query\AllRolesQuery;

class EditRoleController extends BaseController
{
    private $queryBus;

    public function __construct(
        ApplicationService $service,
        QueryBus $queryBus
    ) {
        parent::__construct($service);

        $this->queryBus = $queryBus;
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

        $roles = $this->queryBus->handle(new AllRolesQuery());

        return (new EditRoleViewModel($user, $roles))->view('webloyer::users.edit_role');
    }
}
