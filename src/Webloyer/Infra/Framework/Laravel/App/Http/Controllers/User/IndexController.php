<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\User\UsersDtoDataTransformer;
use Webloyer\App\Service\User\GetUsersService;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\IndexRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\IndexViewModel;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @return ViewModel
     */
    public function __invoke(IndexRequest $request): ViewModel
    {
        assert($this->service instanceof GetUsersService);
        assert($this->service->usersDataTransformer() instanceof UsersDtoDataTransformer);
        $users = $this->service->execute();

        return (new IndexViewModel($users))
            ->setPerPage(10)
            ->view('webloyer::user.index');
    }
}
