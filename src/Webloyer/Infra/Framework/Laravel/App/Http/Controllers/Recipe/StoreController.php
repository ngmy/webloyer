<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Recipe\CreateRecipeRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe\StoreRequest;

class StoreController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function __invoke(StoreRequest $request): RedirectResponse
    {
        $serviceRequest = (new CreateRecipeRequest())
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }
}
