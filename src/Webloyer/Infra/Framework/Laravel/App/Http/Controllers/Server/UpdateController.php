<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Server\UpdateServerRequest;
use Webloyer\Domain\Model\Server\ServerDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Server\UpdateRequest;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest $request
     * @param string        $id
     * @return RedirectResponse
     */
    public function __invoke(UpdateRequest $request, string $id): RedirectResponse
    {
        $serviceRequest = (new UpdateServerRequest())
            ->setId($id)
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));

        assert(!is_null($this->service));

        try {
            $this->service->execute($serviceRequest);
        } catch (ServerDoesNotExistException $exception) {
            abort(404);
        }

        return redirect()->route('servers.index');
    }
}
