<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Webloyer\App\Service\Recipe\UpdateRecipeRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe\UpdateRequest;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest      $request
     * @param string             $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateRequest $request, string $id)
    {
        $serviceRequest = (new UpdateRecipeRequest())
            ->setId($id)
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));
        $this->service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }
}
