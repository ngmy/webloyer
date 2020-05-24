<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Webloyer\App\Service\Recipe\DeleteRecipeRequest;

class DestroyController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new DeleteRecipeRequest())->setId($id);
        $this->service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }
}
