<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Webloyer\App\Service\Recipe\GetRecipeRequest;

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
        $serviceRequest = (new GetRecipeRequest())->setId($id);
        $recipe = $this->service->execute($serviceRequest);

        return view('webloyer::recipes.show')->with('recipe', $recipe);
    }
}
