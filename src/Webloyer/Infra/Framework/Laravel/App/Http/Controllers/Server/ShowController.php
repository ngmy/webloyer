<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use App;
use Webloyer\App\DataTransformer\Project\ProjectsDtoDataTransformer;
use Webloyer\App\Service\Server\{
    GetServerRequest,
    GetServerService,
};
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server\ShowViewModel;

class ShowController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new GetServerRequest())->setId($id);
        assert($this->service instanceof GetServerService);
        $this->service
            ->serverDataTransformer()
            ->setProjectsDataTransformer(App::make(ProjectsDtoDataTransformer::class));
        $server = $this->service->execute($serviceRequest);

        return (new ShowViewModel($server))->view('webloyer::servers.show');
    }
}
