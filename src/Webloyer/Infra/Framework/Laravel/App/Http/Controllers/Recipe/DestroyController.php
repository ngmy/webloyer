<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Recipe\DeleteRecipeRequest;

class DestroyController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function __invoke(string $id): RedirectResponse
    {
        $serviceRequest = (new DeleteRecipeRequest())->setId($id);
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }
}
