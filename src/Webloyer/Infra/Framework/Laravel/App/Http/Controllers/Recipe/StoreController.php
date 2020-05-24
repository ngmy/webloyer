<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Webloyer\App\Service\Recipe\CreateRecipeRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe\StoreRequest;

class StoreController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreRequest $request)
    {
        $serviceRequest = (new CreateRecipeRequest())
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));
        $this->service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }
}
