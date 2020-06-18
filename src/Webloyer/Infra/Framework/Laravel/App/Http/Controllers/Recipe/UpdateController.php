<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Recipe\UpdateRecipeRequest;
use Webloyer\Domain\Model\Recipe\RecipeDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe\UpdateRequest;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest      $request
     * @param string             $id
     * @return RedirectResponse
     */
    public function __invoke(UpdateRequest $request, string $id): RedirectResponse
    {
        $serviceRequest = (new UpdateRecipeRequest())
            ->setId($id)
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));

        assert(!is_null($this->service));

        try {
            $this->service->execute($serviceRequest);
        } catch (RecipeDoesNotExistException $exception) {
            abort(404);
        }

        return redirect()->route('recipes.index');
    }
}
