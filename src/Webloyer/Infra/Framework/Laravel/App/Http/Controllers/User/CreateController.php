<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Common\ServiceBus\QueryBus;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\CreateViewModel;
use Webloyer\Query\AllRolesQuery;

class CreateController extends BaseController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        parent::__construct();

        $this->queryBus = $queryBus;
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $roles = $this->queryBus->handle(new AllRolesQuery());

        return (new CreateViewModel($roles))->view('webloyer::users.create');
    }
}
