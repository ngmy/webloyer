<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Common\ServiceBus\QueryBus;
use Spatie\ViewModels\ViewModel;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\CreateViewModel;
use Webloyer\Query\AllRolesQuery;

class CreateController extends BaseController
{
    /** @var QueryBus */
    private $queryBus;

    /**
     * @param QueryBus $queryBus
     * @return void
     */
    public function __construct(QueryBus $queryBus)
    {
        parent::__construct();

        $this->queryBus = $queryBus;
    }

    /**
     * Handle the incoming request.
     *
     * @return ViewModel
     */
    public function __invoke(): ViewModel
    {
        $roles = $this->queryBus->handle(new AllRolesQuery());

        return (new CreateViewModel($roles))->view('webloyer::user.create');
    }
}
