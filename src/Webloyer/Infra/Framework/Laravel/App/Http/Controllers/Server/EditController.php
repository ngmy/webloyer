<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Spatie\ViewModels\ViewModel;
use Webloyer\App\Service\Server\GetServerRequest;
use Webloyer\Domain\Model\Server\ServerDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server\EditViewModel;

class EditController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return ViewModel
     */
    public function __invoke(string $id): ViewModel
    {
        $serviceRequest = (new GetServerRequest())->setId($id);

        assert(!is_null($this->service));

        try {
            $server = $this->service->execute($serviceRequest);
        } catch (ServerDoesNotExistException $exception) {
            abort(404);
        }

        return (new EditViewModel($server))->view('webloyer::server.edit');
    }
}
