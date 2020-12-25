<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use App;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\Project\ProjectsDtoDataTransformer;
use Webloyer\App\DataTransformer\Servers\ServersDtoDataTransformer;
use Webloyer\App\Service\Server\GetServersService;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Server\IndexRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server\IndexViewModel;

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
        assert($this->service instanceof GetServersService);
        assert($this->service->serversDataTransformer() instanceof ServersDtoDataTransformer);
        $this->service
            ->serversDataTransformer()
            ->serverDataTransformer()
            ->setProjectsDataTransformer(App::make(ProjectsDtoDataTransformer::class));
        $servers = $this->service->execute();

        return (new IndexViewModel($servers))
            ->setPerPage(10)
            ->view('webloyer::server.index');
    }
}
