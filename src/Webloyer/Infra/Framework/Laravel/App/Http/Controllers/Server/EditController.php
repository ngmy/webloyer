<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Webloyer\App\Service\Server\GetServerRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server\EditViewModel;

class EditController extends BaseController
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
        assert(!is_null($this->service));
        $server = $this->service->execute($serviceRequest);

        return (new EditViewModel($server))->view('webloyer::servers.edit');
    }
}
