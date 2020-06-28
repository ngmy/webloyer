<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Common\App\Service\ApplicationService;
use Common\ServiceBus\QueryBus;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\Service\User\GetUserRequest;
use Webloyer\Domain\Model\User\UserDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\EditRoleViewModel;
use Webloyer\Query\AllRolesQuery;

class EditRoleController extends BaseController
{
    /** @var QueryBus */
    private $queryBus;

    /**
     * @param ApplicationService $service
     * @param QueryBus           $queryBus
     * @return void
     */
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
     * @return ViewModel
     */
    public function __invoke(string $id): ViewModel
    {
        $serviceRequest = (new GetUserRequest())->setId($id);

        assert(!is_null($this->service));

        try {
            $user = $this->service->execute($serviceRequest);
        } catch (UserDoesNotExistException $exception) {
            abort(404);
        }

        $roles = $this->queryBus->handle(new AllRolesQuery());

        return (new EditRoleViewModel($user, $roles))->view('webloyer::user.edit-role');
    }
}
